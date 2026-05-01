<?php
session_start();
include "connect.php";
header('Content-Type: application/json');

// to json 
$data = json_decode(file_get_contents('php://input'), true);
$categoryID = $data['categoryID'] ?? null;

if (!$categoryID) {
    echo json_encode(["status" => "error", "message" => "No category ID provided"]);
    exit;
}

try {
    //Delete Budget
    $stmt = $conn->prepare("DELETE FROM Budget WHERE categoryID = ?");
    $stmt->bind_param("i", $categoryID);

    if ($stmt->execute()) {
        
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No budget found with that ID"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
$conn->close();
?>