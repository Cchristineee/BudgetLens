<?php 
session_start();

include "connect.php";

/* The user should be able to have their username show up since they're 
 already logged in ★ */ 

if (!isset($_SESSION["username"])) {
    header("Location: Login.html");
    exit();
}

$username = $_SESSION["username"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if(empty($new_password) || empty($confirm_password)) {
        $message = "Please fill in all fields.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE user_data SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashed_password, $username);

    if ($stmt->execute()) {
        $message = "Password updated successfully!";
        } else {
        $message = "Something went wrong.";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Change Password</title>
    <link rel="stylesheet" href="change_password.css">
</head>
<body>

    <header class="top-header">
        <div class="logo">Budget<span>Lens</span></div>
        <a href="Home.php" class="signup-btn">Back to Dashboard</a>
    </header>

    <main class="page-wrapper">
        <section class="password-card">
            <h1>Wanna change password?</h1>
                <p class="subtitle">Enter a new password below</p>
        
                <!-- To see success / error messages ★ -->
                <?php if (!empty($message)): ?>
                <p class="message"><?php echo $message; ?></p>
                <?php endif; ?>

            <form method="POST" action="change_password.php">
                <label>USERNAME</label>
                <!-- So the page knows who is logged in★ -->
                <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled>

                <label for="new-password">NEW PASSWORD</label>
                <input type="password" id="new-password" 
                name="new_password" placeholder="New Password....">

                <label for="confirm-password">CONFIRM PASSWORD</label>
                <input type="password" id="confirm-password" 
                name="confirm_password" placeholder="Confirm Password....">

                <button type="submit">Change Password</button>
            </form>

        <p class="back-login">Remember your password?
            <a href="Login.html">Back to login</a>
        </p>
        </section>
</body>
</html>


