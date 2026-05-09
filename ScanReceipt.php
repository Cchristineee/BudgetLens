<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "connect.php";

//Initialize ❤
$receiptData = null;
$errorMsg = null;

//get category for dropdown ❤
$categories = [];
$catResult = $conn->query("SELECT global_categoryID, name FROM Global_Category ORDER BY name ASC");
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

//Initialize ❤
$receiptItems = [];



// Gets user's shopping list items across all their lists ❤
$userItems = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Joins item -> Shopping_List -> Users_Shopping_List table b/c i need to see the items that belong to a user ❤
    $itemQuery = "SELECT DISTINCT i.item_name 
                  FROM item i
                  INNER JOIN Shopping_List sl ON i.listID = sl.listID 
                  INNER JOIN User_Shopping_List usl ON sl.listID = usl.listID 
                  WHERE usl.userID = ?";
    
    if ($iStmt = $conn->prepare($itemQuery)) {
        $iStmt->bind_param("i", $userId);
        $iStmt->execute();
        $iResult = $iStmt->get_result();
        
        while ($row = $iResult->fetch_assoc()) {

            // Store as lowercase and trimmed for cleaner matching ❤
            $userItems[] = strtolower(trim($row['item_name']));
        }
        $iStmt->close();
    }
}
//Inserted image will go into Receipt Table 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['receipt'])) {
    $stmt = $conn->prepare("INSERT INTO Receipt (userID, date) VALUES (?, NOW())");
    $user_id = $_SESSION['user_id'] ?? null; 
    $stmt->bind_param("i", $user_id); 

    if ($stmt->execute()) {
        $receiptID = $conn->insert_id;
        $_SESSION['receiptID'] = $receiptID;
    } else {
        die("Database Error: Could not create Receipt record. " . $conn->error);
    }
    // CURL sending receipt image to Python (OCR/API) ❤
    $target_url = 'http://127.0.0.1:5050/upload'; 
    
    if (!file_exists($_FILES['receipt']['tmp_name'])) {
        $errorMsg = "PHP Error: File was not uploaded to the temporary folder.";
    } else {
        $cFile = new CURLFile($_FILES['receipt']['tmp_name'], $_FILES['receipt']['type'], $_FILES['receipt']['name']);
        $post_data = array('file' => $cFile, 'receiptID' => $receiptID);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); 

        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $errorMsg = "CURL Error: " . curl_error($ch);
        } else {
            //  Decode ❤
            $receiptData = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = "Invalid JSON response from AI server. Raw output: " . htmlspecialchars($result);
                $receiptData = null;
            } elseif (isset($receiptData['error'])) {
                $errorMsg = "AI Server Error: " . htmlspecialchars($receiptData['error']);
                $receiptData = null;
            }
        }
        //curl_close($ch);
        $receiptItems = [];

//get info from current receipt for population 
$stmt = $conn->prepare("
    SELECT receipt_itemID, receipt_item_name, receipt_item_price, categoryID
    FROM Receipt_item
    WHERE receiptID = ?
");

$stmt->bind_param("i", $receiptID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $receiptItems[] = $row;
}
    }

}


/* (DOES NOT WORK DO NOT UNCOMMENT ) to upload the receipts to backend ★  
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
        /* $allowedTypes = ["jpg", "jpeg", "png", "pdf"];

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
}*/


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Scan Receipt</title>
    <link rel="stylesheet" href="ScanReceipt.css?v=1.5">
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
        <p>Drag & Drop or click to browse · JPG, JPEG, PNG)</p>
        
       <!-- original ver. but did not work to upload image 

        <form action="#" method="POST" enctype="multipart/form-data">
            <label for="receiptUpload" class="file-btn">Choose File</label>
            <input type="file" id="receiptUpload" name="receipt" accept=".jpg,.jpeg,.png,.pdf">
        </form>
        ★ -->
        <!-- commeted out cause was not working 
        <form action="ScanReceipt.php" method="POST" enctype="multipart/form-data">
        <p>Please select your receipt:</p> -->
    
        <!-- The 'for' attribute MUST match the ID of the input below -->
        <!--<label for="receiptUpload" class="file-btn">Choose Receipt Image</label>
    
         <input type="file" id="receiptUpload" name="receipt" 
        accept=".jpg,.jpeg,.png,.pdf" required>

        <br>

        <button type="submit" class="save-btn">Upload and Scan</button>
        </form>
        -->

    <!-- Upload image/receipt -->
    <form method="POST" enctype="multipart/form-data">

    <input type="file" name="receipt" required>
    <button type="submit" class="save-btn">Upload and Scan</button>
    </form>

        <?php if (!empty($uploadMessage)): ?>
            <p class="upload-message"><?php echo $uploadMessage; ?></p>
        <?php endif;?>
    </section>

    <hr> 

    <!-- OCR Section Preview ★ ❤ -->
    <?php if (!empty($receiptItems)): ?>
    <form action="update_items.php" method="POST">
    <section class="ocr-section">
    <h2>OCR Results Preview</h2>
    <div class="preview-message">Confirm items before saving</div> 

    <div class="results-card">
        <table>
            <thead>
                <tr>
                    <th>ITEM NAME</th>
                    <th>CATEGORY</th>
                    <th>PRICE</th>
                    <th>MATCH</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($receiptItems as $index => $item): ?>   <!-- Needed for Match ❤ -->
            <tr>

                <!-- hidden from users but needed to get  DB ID ❤ -->
                <input type="hidden" 
                       name="items[<?php echo $index; ?>][id]" 
                       value="<?php echo $item['receipt_itemID']; ?>">

                <!-- NAME ❤ -->
                <td>
                    <input type="text" 
                           name="items[<?php echo $index; ?>][name]" 
                           value="<?php echo htmlspecialchars($item['receipt_item_name']); ?>">
                </td>

                <!-- CATEGORY but can only pick from category from table Global_Category ❤ -->
                <td>
                    <select name="items[<?php echo $index; ?>][categoryID]" class="category-select">
                        <option value="">Select Category</option>

                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['global_categoryID']; ?>"
                                <?php echo ($item['categoryID'] == $cat['global_categoryID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </td>

                <!-- PRICE ❤ -->
                <td>
                    <input type="number" step="0.01"
                           name="items[<?php echo $index; ?>][price]" 
                           value="<?php echo htmlspecialchars($item['receipt_item_price']); ?>">
                </td>

                <!-- MATCH  match looks to see if a receipt item has the same exact name as an item in user shopping list 
                 if true a match icon will pop up if not no match (once saved changes item will be removed from users shopping list ❤ -->
                <td>
                    <?php 
                        $currentReceiptItem = strtolower(trim($item['receipt_item_name']));
                        $isMatch = in_array($currentReceiptItem, $userItems);
                    ?>

                    <?php if ($isMatch): ?>
                            <span class="match">✓ Match</span>
                            <input type="hidden" name="items[<?= $index?>][match]" value="1">
                    <?php else: ?>
                            <span class="no-match">No Match</span>
                             <input type="hidden" name="items[<?= $index ?>][match]" value="0">
                    <?php endif; ?>
                </td>

            </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
            <!-- Confirm and Cancel Button -->
        <div class="action-buttons">
            <button class="cancel-btn" onclick="window.location.href='ScanReceipt.php'">Cancel</button>
            <button type="submit" class="save-btn">Confirm & Save</button>
        </div>
    </div>
</section>
</form>


            <!-- Error messages -->
<?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($errorMsg)): ?>
<section class="ocr-section">
    <p style="color: red;"><strong>Error:</strong> <?php echo $errorMsg; ?></p>
</section>
<?php endif; ?>

</main>
</div> 
</body>
</html>