<?php
session_start();
include "connect.php";
//THIS IS TO UPDATE ITEMS FOR RECEIPT
$uID = $_SESSION['user_id'] ?? null;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['items'])) {

    $items = $_POST['items'];


        //to remove matched items from shopping list ❤
        foreach ($items as $index => $itemData) {

            if (!empty($itemData['match']) && $itemData['match'] == 1) {
                
                $nameToDelete = $itemData['name'];
                $stmt = $conn->prepare("
                    DELETE FROM item 
                    WHERE item_name = ?
                ");
    
                $stmt->bind_param("s", $nameToDelete);
                $stmt->execute();
            }
        }

        //if users make changes to receipt,it will be updated to database (Save Changes button) ❤
    $stmt = $conn->prepare("
        UPDATE Receipt_item 
        SET receipt_item_name = ?, 
            receipt_item_price = ?, 
            categoryID = ?
        WHERE receipt_itemID = ?
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    foreach ($items as $item) {

        $id = (int)($item['id'] ?? 0);
        $name = $item['name'] ?? '';
        $price = (float)($item['price'] ?? 0);
        $categoryID = (int)($item['categoryID'] ?? 1);
    
        if ($id <= 0) continue;
    
        $stmt->bind_param("sdii", $name, $price, $categoryID, $id);
        $stmt->execute();
    
        $infoStmt = $conn->prepare("
            SELECT receipt_item_price, categoryID 
            FROM Receipt_item 
            WHERE receipt_itemID = ?
        ");
        $infoStmt->bind_param("i", $id);
        $infoStmt->execute();
        $infoResult = $infoStmt->get_result();
    
        if ($infoResult->num_rows > 0) {
            $row = $infoResult->fetch_assoc();
    
            $price = $row['receipt_item_price'];
            $catID = $row['categoryID'];
            
            //Then the budget will be uupdated since you confirmed you bought these items ❤
            if ($catID) {
                $updateBudget = $conn->prepare("
                    UPDATE Budget 
                    SET remaining_amount_left = remaining_amount_left - ? 
                    WHERE userID = ? AND categoryID = ?
                ");
                $updateBudget->bind_param("dii", $price, $uID, $catID);
                $updateBudget->execute();
                $updateBudget->close();
            }
        }
    
        $infoStmt->close();
    }
    

    header("Location: ScanReceipt.php");
    exit();
}
?>          