<?php
session_start();
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $userID = $_POST['user_id'] ?? null;

    if (!$userID) {
        exit("Invalid user ID");
    }

    // Delete user ❤
    $stmt = $conn->prepare("DELETE FROM user_data WHERE userID = ?");
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}
?>