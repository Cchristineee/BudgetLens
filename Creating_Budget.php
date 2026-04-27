<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();
include "connect.php";
header('Content-Type: application/json');

// convert user input using font
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// receives from php 
if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received by PHP"]);
    exit;
}

// attribute
$userID = $_SESSION['user_id'] ?? null;
$categoryID = $data['categoryID'] ?? null; 
$budgetLimit = $data['budgetLimit'] ?? 0;
$frequencyID = $data['frequencyID'] ?? 1; 
$currentDate = date('Y-m-d');  //currently time isnt working need to fix 


  //cant be empty dont change
  //if (empty($categoryID)) {
 //  echo json_encode(["status" => "error", "message" => "Category is required"]);
   // exit;
//}

// Category cant be null for budget 
if (is_null($categoryID) || $categoryID === '') {
    echo json_encode(["status" => "error", "message" => "PHP received a NULL categoryID"]);
    exit;
}
// must have user 
if (!$userID) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

try {
    $sql = "INSERT INTO Budget (userID, categoryID, budgetLimit, remaining_amount_left, Budget_frequencyID, last_reset_date) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("iiddis", $userID, $categoryID, $budgetLimit, $budgetLimit, $frequencyID, $currentDate);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Budget created"]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Error: " . $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Server Exception: " . $e->getMessage()]);
}

$conn->close();
?>