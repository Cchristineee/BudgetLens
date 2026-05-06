<?php
session_start();
include "connect.php";

header('Content-Type: application/json');

$listID = $_POST['list_id'] ?? null;
$username = $_POST['share_username'] ?? null;


if (!$listID || !$username) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// Find user ❤
$stmt = $conn->prepare("SELECT userID FROM user_data WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User does not exist"]);
    exit;
}

$user = $result->fetch_assoc();
$sharedWithUserID = $user['userID'];

// Prevent sharing with yourself ❤
if ($sharedWithUserID == $_SESSION['userID']) {
    echo json_encode(["status" => "error", "message" => "You cannot share with yourself"]);
    exit;
}

// Check duplicate ❤
$check = $conn->prepare("SELECT * FROM User_Shopping_List WHERE listID = ? AND userID = ?");
$check->bind_param("ii", $listID, $sharedWithUserID);
$check->execute();

if ($check->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Already shared with this user"]);
    exit;
}

// Insert ❤
$insert = $conn->prepare("INSERT INTO User_Shopping_List (listID, userID, is_shared) VALUES (?, ?, 1)");
$insert->bind_param("ii", $listID, $sharedWithUserID);

if ($insert->execute()) {

    $update = $conn->prepare("UPDATE User_Shopping_List SET is_shared = 1 WHERE listID = ?");
    $update->bind_param("i", $listID);
    $update->execute();

    echo json_encode(["status" => "success", "message" => "List shared successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>