<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "connect.php";


if(empty($_POST ["username"])){
    die("Username is required");
}

if(empty($_POST ["password"])){
    die("Password is required");
}

$Username = $_POST['username'];
$Password = $_POST['password'];

$hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

$sql = "INSERT into user_data(username,password,roles)VALUES( '$Username','$hashedPassword', 'user')";

if(mysqli_query($conn,$sql)){
// this needs to go to go to dashboard when finished 
    header('location:signup.html');
    exit();
}
    else{
    echo "Error" . mysqli_error($conn);
    }
    
?>
