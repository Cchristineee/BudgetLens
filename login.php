<?php
session_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 1);
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
          
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['userID'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['roles'] = $user['roles']; /* For the admin ★ */

        /* redirect based on role  ★ */
        if ($user['roles'] == 'admin') {
        header('Location: admin_overview.php');
        } else {
        header('location:Home.php');
        }
        exit();
    } else {
        echo "Invalid username or password.";
    }

}
?>