<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Users</title>
    <link rel="stylesheet" href="admin_users.css">
</head>
<body>

    <div class="dashboard-layout">
        
    <!-- Admin Sidebar Nav ★ -->
    <?php include 'admin_sidebar.php'; ?>

    <!-- Main content ★ -->
    <main class="main-content">

        <section class="users-card">
            
            <div class="users-header">
                <h1>All Users</h1>
                <input type="text" placeholder="Search username....">
            </div>
            
            <!-- Hardcoded will reflect db later.... ★ -->
            <div class="users-table">
                <div class="table-header">
                <span>USERNAME</span>
                <span>JOINED</span>
                <span>LIST</span>
                <span>REPORTS</span>
                <span>ACTIONS</span>
            </div>

            <div class="table-row">
                <span>Christine_G</span>
                <span>January 5, 2025</span>
                <span>4</span>
                <span><span class="status active">Active</span></span>
                <span><button class="delete-btn">Delete</button></span>
            </div>

            <div class="table-row">
                <span>bobby_k</span>
                <span>February 12, 2025</span>
                <span>2</span>
                <span><span class="status active">Active</span></span>
                <span><button class="delete-btn">Delete</button></span>
            </div>

            <div class="table-row">
                <span>ariana_B</span>
                <span>March 3, 2026</span>
                <span>6</span>
                <span><span class="status none">None</span></span>
                <span><button class="delete-btn">Delete</button></span>
            </div>
        </div>
      </section>
    </main>
</div>
</body>
</html>