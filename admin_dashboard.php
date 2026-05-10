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
// Joining user_data,user_issue_report, admin_issue_report to find 5 most recent  reports 

$sql = "
    SELECT 
        u.username,
        a.is_completed,
        a.admin_reportID
    FROM admin_issue_report a
    JOIN user_issue_report ur ON a.reportID = ur.reportID
    JOIN user_data u ON ur.userID = u.userID
    ORDER BY a.admin_reportID DESC
    LIMIT 5
";

$result = $conn->query($sql);

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
            <h1 class="admin-title">
                <p>Admin Overview</p>
            </h1>
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
                <h2>Recent Report Activity</h2>
                
                <?php if ($result && $result->num_rows > 0): ?>
        <!-- This will get the top 5 most recent reports and say if it is read or actice ❤ -->
        <?php while ($row = $result->fetch_assoc()): ?>

            <?php
                $status = ($row['is_completed'] == 0) ? "Active" : "Read";
                $statusClass = ($row['is_completed'] == 0) ? "active" : "read";
            ?>

            <div class="activity-row">
                <span class="activity-date <?php echo $statusClass; ?>">
                    <?php echo $status; ?>
                </span>

                <span class="activity-text">
                    Report submitted by @<?php echo htmlspecialchars($row['username']); ?>
                </span>
            </div>

        <?php endwhile; ?>

    <?php else: ?>
        <div class="activity-row">
            <span class="activity-date">--</span>
            <span class="activity-text">No reports found</span>
        </div>
    <?php endif; ?>

            </section>
        </main>
    </body>
</html>