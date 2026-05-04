<?php
include 'connect.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'No input received']);
    exit;
}

$stmt = $conn->prepare("
    UPDATE item 
    SET item_name = ?, item_price = ?, categoryID = ?
    WHERE itemID = ?
");

$stmt->bind_param(
    "sdii",
    $input['item_name'],
    $input['item_price'],
    $input['categoryID'],
    $input['itemID']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}