<?php session_start();
include "connect.php";

// Calculate amount of users ❤

$query = "SELECT COUNT(*) AS total_users FROM user_data";
$result = $conn->query($query);

$totalUsers = 0;
if ($result && $row = $result->fetch_assoc()) 
{
    $totalUsers = $row['total_users'];
}

// Calculate amount of lists ❤

$query = "SELECT COUNT(*) AS total_list FROM User_Shopping_List ";
$result = $conn->query($query);


if ($result && $row = $result->fetch_assoc()) 
{
    $totalList = $row['total_list'];
}

//Calculate Active Reports  needs to be done ❤

// attribute is_completed, 0 means not complete, 1 means completed 

$query = "SELECT COUNT(*) AS active_reports 
        FROM admin_issue_report 
        WHERE is_completed = 0";

$result = $conn->query($query);
$row = $result->fetch_assoc();

$activeCount = $row['active_reports'];

//Calculate categories ❤

$query = "SELECT COUNT(*) AS total_categories FROM Global_Category ";
$result = $conn->query($query);


if ($result && $row = $result->fetch_assoc()) 
{
    $totalcategories= $row['total_categories'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Overview </title>
    <link rel="stylesheet" href="admin_dashboard.css">
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
                    <h2><?php echo $totalUsers; ?></h2>
                    <p>Total Users</p>
                </div>

                <div class="stat-card">
                    <h2><?php echo $totalList; ?></h2>
                    <p>Active List</p>
                </div>

                <div class="stat-card">
                    <h2 class="red-number"> <?php echo $activeCount; ?></h2>
                    <p>Open Reports</p>
                </div>

                <div class="stat-card">
                    <h2><h2><?php echo $totalcategories; ?></h2></h2>
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