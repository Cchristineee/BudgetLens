<?php
session_start();
include "connect.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$listID = $data['list_id'] ?? null;

if (!$listID) {
    echo json_encode(["status" => "error", "message" => "No list ID provided"]);
    exit;
}

try {
    // Deleting from ShoppingList ❤
    $stmt = $conn->prepare("DELETE FROM Shopping_List WHERE listID = ?");
    $stmt->bind_param("i", $listID);

    //Sees if it goes to database ❤
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
$conn->close();
?>