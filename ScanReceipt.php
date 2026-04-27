<?php
session_start()
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

        <form action="#" method="POST" enctype="multipart/form-data">
            <label for="receiptUpload" class="file-btn">Choose File</label>
            <input type="file" id="receiptUpload" name="receipt" accept=".jpg,.jpeg,.png,.pdf">
        </form>
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
            <thread>
                <tr>
                    <th>ITEM NAME</th>
                    <th>PRICE</th>
                    <th>CATEGORY</th>
                    <th>MATCH</th>
                </th>
            </thread>

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
        </div> 

        <div class="action-buttons">
            <button class="cancel-btn">Cancel</button>
            <button class="save-btn">Confirm & Save</button>
        </div>
    </section>
    </main>
</body>
</html>