import os
import json
import time
import random
import warnings
from dotenv import load_dotenv

import pytesseract
from PIL import Image, ImageEnhance

import mysql.connector
from flask import Flask, request, jsonify

from google import genai


# Setup/ Defines what version of gemini we using and connects to api key ❤
warnings.filterwarnings("ignore")

app = Flask(__name__)
load_dotenv()

MODELS = ["gemini-2.5-flash"]

client = genai.Client(
    api_key=os.getenv("GOOGLE_API_KEY")
)

pytesseract.pytesseract.tesseract_cmd = "/opt/homebrew/bin/tesseract"


# Connect to  Database ❤
def get_db():
    return mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="BudgetLens",
        port=3307
    )


def get_category_map():
    db = get_db()
    cursor = db.cursor(dictionary=True)

    cursor.execute("SELECT global_categoryID, name FROM global_category")
    rows = cursor.fetchall()
    cursor.close()
    db.close()

    return {row["name"]: row["global_categoryID"] for row in rows}


# OCR/enhance image 
def extract_text(image_path):
    img = Image.open(image_path).convert("L")
    img = ImageEnhance.Contrast(img).enhance(2.0)

    return pytesseract.image_to_string(img, config="--oem 3 --psm 6").strip()



# Gemini if servers are full it will retry instead of crashing ❤
def call_gemini(prompt):
    last_error = None

    for model in MODELS:
        for attempt in range(5):
            try:
                return client.models.generate_content(
                    model=model,
                    contents=prompt
                )

            except Exception as e:
                last_error = e

                if "503" in str(e) or "UNAVAILABLE" in str(e):
                    wait = (2 ** attempt) + random.uniform(0, 1)
                    print(f"⚠️ Retry in {wait:.2f}s...")
                    time.sleep(wait)
                    continue

                break

        print("❌ Switching model...")

    raise Exception(f"All models failed: {last_error}")


# we gonna output the receipt in this format to have only important information ❤
def parse_with_gemini(raw_text, category_names):
    categories = ", ".join(category_names)

    prompt = f"""
Extract receipt into STRICT JSON ONLY:

{{
  "vendor_name": "string",
  "date": "YYYY-MM-DD",
  "total": 0.0,
  "items": [
    {{
      "name": "string",
      "price": 0.0,
      "category": "string"
    }}
  ]
}}

Rules:
- Use ONLY these categories: {categories}
- Return ONLY valid JSON
- No markdown, no backticks

Receipt:
{raw_text[:3000]}
"""

    response = call_gemini(prompt)
    text = (response.text or "").strip()

    if "```" in text:
        text = text.split("```")[1]
        if text.startswith("json"):
            text = text[4:]
        text = text.strip()

    return json.loads(text)


# Upload endpoint
@app.route("/upload", methods=["POST"])
def upload():
    if "file" not in request.files:
        return jsonify({"error": "No file uploaded"}), 400

    if "receiptID" not in request.form:
        return jsonify({"error": "receiptID is required"}), 400

    file = request.files["file"]
    receipt_id = request.form.get("receiptID")

    os.makedirs("uploads", exist_ok=True)
    filepath = os.path.join("uploads", file.filename)
    file.save(filepath)

    try:
        # OCR ❤
        raw_text = extract_text(filepath)

        # Categories ❤
        category_map = get_category_map()

        # Gemini parsing ❤
        data = parse_with_gemini(raw_text, category_map.keys())

        # DB insert ❤
        db = get_db()
        cursor = db.cursor()

        for item in data.get("items", []):
            category_id = category_map.get(item["category"], 1)

            cursor.execute(
                """
                INSERT INTO Receipt_Item
                (receiptID, receipt_item_name, receipt_item_price, categoryID)
                VALUES (%s, %s, %s, %s)
                """,
                (
                    receipt_id,
                    item["name"],
                    item["price"],
                    category_id
                )
            )
            # used to get receipt_itemID
            item_id = cursor.lastrowid
            item["id"] = item_id
            
        db.commit()
        db.close()

        return jsonify(data)

    except json.JSONDecodeError:
        return jsonify({"error": "Gemini returned invalid JSON"}), 500

    except Exception as e:
        print("Server Error:", e)
        return jsonify({"error": str(e)}), 500


# ----------------------------
# Run server
# ----------------------------
if __name__ == "__main__":
    print("🚀 OCR Server running on http://127.0.0.1:5050")
    app.run(host="127.0.0.1", port=5050, debug=True)