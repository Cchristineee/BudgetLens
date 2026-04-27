<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Edit Budget</title>
    <link rel="stylesheet" href="Edit_Budget.css">
</head>
<body>

    <div class="dashboard-layout">

    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php';?>

    <main class="main-content">
    <p class="breadcrumb">My Budget &gt; <span>Food</span></p>

    <div class="edit-header">
        <a href="MyBudget.php" class="back-arrow">←</a>
        <h1>Edit Budget: Food</h1>
    </div>

    <!-- These are hardcodded, will add from db later... ★ -->
    <div class="edit-card">

        <label>Category Name</label>
        <input type="text" placeholder="Food">

        <label>Budget Limit</label>
        <input type="text" placeholder="$300.00">

        <label>Reset Period</label>
        <select>
            <option>Montly</option>
            <option>Weekly</option>
        </select>

        <hr>

        <h2>This Month's Transactions</h2>

        <div class="transaction-row">
            <div>
                <h4>Grocery run - Whole Foods</h4>
                <p>Mar 15, 2026</p>
            </div>
            <strong>-$87.40</strong>
        </div>

         <div class="transaction-row">
            <div>
                <h4>Trader Joe's</h4>
                <p>Mar 10, 2026</p>
            </div>
            <strong>-$113.10</strong>
        </div>

        <div class="button-row">
            <button class="delete-btn">Delete Category</button>
            <button class="save-btn">Save Changes</button>
        </div>
    </div>  
</main>
</div>
</html>