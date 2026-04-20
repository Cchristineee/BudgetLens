<?php
session_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 0);
include "connect.php"; 

header('Content-Type: application/json');

//neccessary attribute ❤
$data = json_decode(file_get_contents('php://input'), true);
$name = $data['list_name'] ?? '';
$uID = $_SESSION['user_id'] ?? null; 

if (!$uID) {
    echo json_encode(["status" => "error", "message" => "User not logged in. Please log in first."]);
    exit;
}

//Constraint for list name cant be empty ❤
if (empty($name)) {
    echo json_encode(["status" => "error", "message" => "List name cannot be empty."]);
    exit;
}



try {
    
    $conn->begin_transaction();

    // 1. Insert into Shopping_List ❤
    $stmt1 = $conn->prepare("INSERT INTO Shopping_List (list_name) VALUES (?)");
    $stmt1->bind_param("s", $name);
    $stmt1->execute();
    
    $newListID = $conn->insert_id;

    // 2. Insert into User_Shopping_List ❤
    $stmt2 = $conn->prepare("INSERT INTO User_Shopping_List (listID, userID) VALUES (?, ?)");
    $stmt2->bind_param("ii", $newListID, $uID);
    $stmt2->execute();

    $conn->commit();
    echo json_encode(["status" => "success"]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();
?>