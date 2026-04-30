<?php
session_start();

/* to upload the receipts to backend ★ */ 
$uploadMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_FILES["receipt"]) && $_FILES["receipt"]["error"] === 0) {

        $uploadDir = "uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES["receipt"]["name"]);
        $fileTmp = $_FILES["receipt"]["tmp_name"];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        /* Allows only jpg, png, & pdfs to be uploaded ★ */ 
        $allowedTypes = ["jpg", "jpeg", "png", "pdf"];

        if (in_array($fileExt, $allowedTypes)) {

            $newFileName = uniqid("receipt_", true) . "." . $fileExt;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $destination)) {
                $uploadMessage = "Receipt uploaded successfully!";
            } else {
                $uploadMessage = "File upload failed.";
            }

        } else {
            $uploadMessage = "Only JPG, PNG, and PDF files are allowed.";
        }

    } else {
        $uploadMessage = "Please choose a receipt file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Scan Receipt</title>
    <link rel="stylesheet" href="ScanReceipt.css">
</head>
<body>

    <div class="dashboard-layout">

    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php';?>

    <main class="main-content">
        <h1>Scan Receipt</h1>

    <!-- Upload Box ★ -->
    <section class = "upload-box">
        <div class = "upload-icon">📸</div>

        <h2>Upload Receipt Image</h2>
        <p>Drag & Drop or click to browse · JPG, PNG, PDF</p>
        
       <!-- original ver. 
        <form action="#" method="POST" enctype="multipart/form-data">
            <label for="receiptUpload" class="file-btn">Choose File</label>
            <input type="file" id="receiptUpload" name="receipt" accept=".jpg,.jpeg,.png,.pdf">
        </form>
        ★ -->

        <form action="ScanReceipt.php" method="POST" enctype="multipart/form-data">
            <label for="receiptUpload" class="file-btn">Choose File</label>

            <input type="file" id="receiptUpload" name="receipt" 
                accept=".jpg,.jpeg,.png,.pdf" required>

            <button type="submit" class="upload-btn">Upload Receipt</button>
        </form>

        <?php if (!empty($uploadMessage)): ?>
            <p class="upload-message"><?php echo $uploadMessage; ?></p>
        <?php endif;?>
    </section>

    <hr> 

     <!-- OCR Section Preview ★ -->
    <section class = "ocr-section">
        <h2>OCR Results Preview</h2>

        <div class="preview-message">
        <!-- After upload, OCR extracts items & prices. User confirms before saving -->
        Confirm items before saving
        </div> 

        <!-- Hard coded values, will use db later -->
        <div class="results-card">
        <table>
            <thead>
                <tr>
                    <th>ITEM NAME</th>
                    <th>PRICE</th>
                    <th>CATEGORY</th>
                    <th>MATCH</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Cheddar Cheese</td>
                    <td>$5.99</td>
                    <td>
                        <select>
                            <option>Food</option>
                            <option>Health</option>
                            <option>Household</option>
                            <option>Other</option>
                        </select>
                    </td>
                    <td><span class="match">✓ Match</span></td>
                </tr>

                <tr>
                    <td>Vitamins</td>
                    <td>$18.00</td>
                    <td>
                        <select>
                            <option>Food</option>
                            <option>Health</option>
                            <option>Household</option>
                            <option>Other</option>
                        </select>
                    </td>
                    <td><span class="no-match"> No Match</span></td>
                </tr>
            </tbody>
        </table>

        <div class="action-buttons">
            <button class="cancel-btn">Cancel</button>
            <button class="save-btn">Confirm & Save</button>
        </div>
    </section>
    </main>
</body>
</html>