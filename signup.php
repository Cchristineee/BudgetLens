<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "connect.php";


if (empty($_POST["username"])) {
    header("Location: signup.html?username_error=Username+is+required");
    exit();
}

if (empty($_POST["password"])) {
    header("Location: signup.html?password_error=Password+is+required");
    exit();
}

if (empty($_POST["confirm-password"])) {
    header("Location: signup.html?confirm_error=Please+confirm+your+password");
    exit();
}

$Username = trim($_POST['username']);
$Password = $_POST['password'];
$confirmPassword = $_POST['confirm-password'];

//  Check if passwords match ❤
if ($Password !== $confirmPassword) {
    header("Location: signup.html?error=Passwords+do+not+match");
    exit();
}

// Hash password ❤
$hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

// Check if username exists ❤
$checkStmt = $conn->prepare("SELECT userID FROM user_data WHERE username = ?");
$checkStmt->bind_param("s", $Username);
$checkStmt->execute();
$checkStmt->store_result();

//checks if username is taken ❤
if ($checkStmt->num_rows > 0) {
    header("Location: signup.html?error=Username+already+taken");
    exit();
}

// Insert user to database ❤
$stmt = $conn->prepare("INSERT INTO user_data (username, password, roles) VALUES (?, ?, 'user')");
$stmt->bind_param("ss", $Username, $hashedPassword);

if ($stmt->execute()) {

    // get the new user ID ❤
    $userID = $stmt->insert_id;
    //store it and username
    $_SESSION['user_id'] = $userID;
    $_SESSION['username'] = $Username;

    // redirect
    header("Location: Home.php");
    exit();

} else {
    echo "Error: " . $stmt->error;
}
?>