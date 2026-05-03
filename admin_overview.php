<?php session_start();
include "connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Overview </title>
    <link rel="stylesheet" href="admin_overview.css">
</head>
<body>
    <div class="dashboard-layout">
        
    <!-- Admin Sidebar Nav ★ -->
    <?php include 'admin_sidebar.php'; ?>

    <!-- Main Content ★ -->
        <main class="main-content">
            <header class="dashboar-header">
                <p>Admin Overview</p>
            </header>
            <!-- Hardcoded: will reflect database later... ★ -->
            <section class="stats-grid">

                <div class="stat-card">
                    <h2>142</h2>
                    <p>Total Users</p>
                </div>

                <div class="stat-card">
                    <h2>89</h2>
                    <p>Active List</p>
                </div>

                <div class="stat-card">
                    <h2 class="red-number">3</h2>
                    <p>Open Reports</p>
                </div>

                <div class="stat-card">
                    <h2>12</h2>
                    <p>Categories</p>
                </div>
            </section>

            <!-- Activity Card ★ -->
            <section class="activity-card">
                <h2>Recent Activity</h2>
                
                <div class="activity-row">
                    <span class="activity-date">2026 - 03 - 23 8:55</span>
                    <span class="activity-text">user @bobby_k created a list "Vegan ingredients"</span>
                </div>

                <div class="activity-row">
                    <span class="activity-date">2026 - 03 - 23 7:35</span>
                    <span class="activity-text">Report submitted by @ariana</span>
                </div>
            </section>
        </main>
    </body>
</html>