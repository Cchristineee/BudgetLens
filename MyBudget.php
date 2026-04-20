<?php
session_start()
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - My Budget</title>
     <link rel="stylesheet" href="MyBudget.css">
</head>
<body>
    <div class="dashboard-layout">
    
    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php'; ?>

     <main class="main-content">
        <div class="page-header">
            <h1>My Budget</h1>
            <a href="#" class="create-budget-btn">+ Create Budget</a>
        </div>

        <!-- Spending Status cards ★ -->
        <section class="budgeting-cards">
            <div class="budgeting-card">
                <h3 class ="card-title food">🍱 Food</h3>
                <p class="card-text">$200.50 of $300.00 spent</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 67%;"></div>
            </div>

            <div class="budgeting-card">
                <h3 class ="card-title health">❤️ Health</h3>
                <p class="card-text">$150.00 of $100.00 spent</p>
                <div class="progress-bar">
                    <div class="progress-fill danger" style="width: 100%;"></div>
            </div>

            <div class="budgeting-card">
                <h3 class ="card-title household">🏠 Household</h3>
                <p class="card-text">$300.00 of $500.00 spent</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 60%;"></div>
            </div>  

            <div class="budgeting-card">
                <h3 class ="card-title clothing">🛍️ Clothing</h3>
                <p class="card-text">$250.01 of $1,100.00 spent</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 23%;"></div>
            </div>     
</section>
</body>
</html>