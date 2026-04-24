<?php
session_start();
include "connect.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$itemID = $data['item_id'] ?? null;
$uID = $_SESSION['user_id'] ?? null;

if (!$itemID) {
    echo json_encode(["status" => "error", "message" => "No item ID provided"]);
    exit;
}

$conn->begin_transaction();
try{
// getting the price and category BEFORE deleting ❤
$infoStmt = $conn->prepare("SELECT item_price, categoryID FROM item WHERE itemID = ?");
$infoStmt->bind_param("i", $itemID);
$infoStmt->execute();
$infoResult = $infoStmt->get_result();

if ($infoResult->num_rows === 0) {
    throw new Exception("Item not found");
}

$item = $infoResult->fetch_assoc();
$price = $item['item_price'];
$catID = $item['categoryID'];

// UPDATE the budget (subtracting) ❤
if ($catID) {
    $updateBudget = $conn->prepare("UPDATE budget SET remaining_amount_left = remaining_amount_left - ? WHERE userID = ? AND categoryID = ?");
    $updateBudget->bind_param("dii", $price, $uID, $catID);
    $updateBudget->execute();
}

// DELETE the item ❤
$deleteStmt = $conn->prepare("DELETE FROM item WHERE itemID = ?");
$deleteStmt->bind_param("i", $itemID);
$deleteStmt->execute();

// save changes ❤
$conn->commit();
echo json_encode(["status" => "success"]);

} catch (Exception $e) {

// If anything fails, undo everything ❤
$conn->rollback();
echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();
?>