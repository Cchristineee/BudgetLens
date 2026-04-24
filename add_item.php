<?php
session_start();
include "connect.php";
header('Content-Type: application/json');

// convert user input ❤
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// receives from php ❤
if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received by PHP"]);
    exit;
}

// attribute ❤
$itemName = $data['item_name'] ?? '';
$listID   = $data['list_id'] ?? null; 
$price    = (isset($data['item_price']) && $data['item_price'] !== '') ? (float)$data['item_price'] : 0.00;
$categoryID = $data['categoryID'] ?? null;

// cant be empty dont change ❤
if (empty($itemName)) {
    echo json_encode(["status" => "error", "message" => "Item name is empty"]);
    exit;
}
if (!$listID) {
    echo json_encode(["status" => "error", "message" => "List ID is missing"]);
    exit;
}
if (!$categoryID) {
    echo json_encode(["status" => "error", "message" => "Category is required"]);
    exit;
}

// insert ❤

try {
    
    $stmt = $conn->prepare("INSERT INTO item (listID, item_name, item_price, categoryID) VALUES (?, ?, ?, ?)");
    
    // i = int, s = string, d = decimal/double
    $stmt->bind_param("isdi", $listID, $itemName, $price,$categoryID);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Error: " . $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server Exception: " . $e->getMessage()]);
}

$conn->close();
?>
