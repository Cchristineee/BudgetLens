<?php session_start();
include "connect.php";

// makes sure you are logged in ❤

if (!isset($_SESSION['user_id'])) {
    die("Error: You are not logged in. <a href='login.php'>Login here</a>");
}
$uID = $_SESSION['user_id']; 

//used for Spending Staus image. if remaining_amount is negative we know they went overbudget in a category ❤
$overBudget = false;

$sql = "SELECT COUNT(*) as total FROM budget WHERE userID = ? AND remaining_amount_left < 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

//if any budget is overbudget we change overbudget to true and image changes ❤
if ($row['total'] > 0) {
    $overBudget = true; 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Dashboard/Home</title>
    <link rel="stylesheet" href="Home.css">
</head>
<body>

    <div class="dashboard-layout">
        
    <!-- Sidebar Nav -->
    <?php include 'sidebar.php'; ?>
    
        <!--Main Content-->
        <main class="main-content">
            <header class="dashboard">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <!-- I want to see if we can use real date/time that would sync -->
                <p>Here's your budget overview for April 2026</p>
            </header>

        <!-- Spending Status -->
         <section class="top-cards">
            <div class="card status-card">
                <h3>Spending Status</h3>

            <!--  Spending Status: If overbudget it will show bad icon else good icon ❤ -->
                <div class="status-display">
                <?php if ($overBudget): ?>
                    <span class="status-text">Bad</span>
                    <span class="warning-circle">!</span>
                    <?php else: ?>
                    <span class="status-text" style="color: green;">Good</span>
                    <span class="warning-circle" style="background-color: #008000;">✔</span>
                    <?php endif; ?>
                </div>

                 <!-- Shows how many categories are overbudget ❤ -->
                <?php
              echo "<p class='status-note'>You've exceeded " . $row['total'] . " budget categories this month  " . $_SESSION['username'] . "</p>";
              ?>

            </div>

            <!-- Overview card -->
            <div class="card overview-card">
                <h3>Monthly Overview</h3>

                <p class="overview-label">Total spent this month</p>

                <div class="overview-amount-line">
                    <span class="overview-amount">$900.51</span>
                    <span class="overview-budget-text">of $2,000.00 budget</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>

                <div class="progress-meta">
                    <span>45% used</span>
                    <span>$1,099.49 remaining</span>
                </div>
            </div>
        
             <!-- Ugent Items-->
            <div class="card urgent-card">
            <h3>Urgent Items</h3>

            <div class="item-row">
            <div class="item-info">
            <h4>Cheese</h4>
            <p>Family shopping list</p>
            </div>
            <button class="item-btn">Add to cart</button>
            </div>

            <div class="item-row">
            <div class="item-info">
            <h4>Multivitamins</h4>
            <p>My Medicine</p>
            </div>
            <button class="item-btn">Add to cart</button>
            </div>

            <a href="MyList.php" class="view-all-link">View all items →</a>
            </div>       
     </section>
</body>
</html>