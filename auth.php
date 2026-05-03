<!-- Admin Authentication -->
<?php
session_start();


if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
?>