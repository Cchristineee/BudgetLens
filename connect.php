<!-- this allows you to connect to myphp locally (please dont change ) --> 

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BudgetLens";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>