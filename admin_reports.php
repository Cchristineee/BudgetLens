<?php
session_start();
include "connect.php";

// Get all reports + status 
$query = " SELECT r.reportID, r.subject, r.report, r.issue_type, r.userID, a.is_completed 
FROM user_issue_report r 
LEFT JOIN admin_issue_report a 
ON r.reportID = a.reportID
 ORDER BY r.reportID 
 DESC ";
  $result = $conn->query($query); 
  $reports = []; while ($row = $result->fetch_assoc()) { $reports[] = $row; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Reports</title>
    <link rel="stylesheet" href="admin_reports.css">
</head>
<body>
    <div class="dashboard-layout">
        
    <!-- Admin Sidebar Nav ★ -->
    <?php include 'admin_sidebar.php'; ?>

    <!-- Main content ★ -->
    <main class="reports-page">
        <header class = "reports-title">
            <h1>User Reports</h1>
            <p>1 new report • 6 total</p>
        </header>

        <section class="reports-layout">
        <!-- Left Pannel ★ -->
        <aside class="reports-list-panel">
    <div class="filters">
        <div class="filter-row">
            <select>
                <option>All Status</option>
                <option value="0">Active</option>
                <option value="1">Read</option>
            </select>

            <select>
                <option>All Categories</option>
                <option>Bug</option>
                <option>Data Issue</option>
                <option>Feature Request</option>
                <option>Other</option>
            </select>
        </div>
    </div>

    <?php foreach ($reports as $report): ?>
        <a href="?id=<?php echo $report['reportID']; ?>" style="text-decoration:none; color:inherit;">
            <div class="report-card <?php echo ($report['is_completed'] == 0) ? 'active' : ''; ?>">
                
                <div>
                    <?php
                    $type = strtolower(trim($report['issue_type']));

                    // ICONS
                    if ($type === 'feature') {
                        echo '<span class="feature">♧</span>';
                    } elseif ($type === 'feedback') {
                        echo '<span class="feedback">▱</span>';
                    } elseif ($type === 'bug') {
                        echo '<span class="bug">☼</span>';
                    } elseif ($type === 'other') {
                        echo '<span class="other">☼</span>';
                    } elseif ($type === 'data') {
                        echo '<span class="data">☼</span>';
                    } else {
                        echo '<span class="bug">☼</span>'; // fallback
                    }
                    ?>

                    <!-- ISSUE TYPE PILL -->
                    <span class="pill <?php echo $type; ?>">
                        <?php echo htmlspecialchars($report['issue_type']); ?>
                    </span>

                    <!-- STATUS -->
                    <?php if ($report['is_completed'] == 0): ?>
                        <span class="pill new">Active</span>
                    <?php else: ?>
                        <span class="pill read">Read</span>
                    <?php endif; ?>
                </div>

                <h3><?php echo htmlspecialchars($report['subject']); ?></h3>
                <p>User ID: <?php echo $report['userID']; ?></p>
                <small>Report #<?php echo $report['reportID']; ?></small>

            </div>
        </a>
    <?php endforeach; ?>
</aside>

        <!-- Right Pannel ★ -->  
        <!-- Hardcoded ...... ★ -->  
        <section class="report-detail-panel">
            <div class="detail-top">
                <div class="detail-tags">
                <span class="icon green">▱</span>
                <span class="pill feedback">Feedback</span>
                <span class="pill read">READ</span>
            </div>

            <button class="archive-btn">Set as Read</button>
        </div>

        <h2>Love the new dashboard!</h2>
        <p class="date">2026-05-03 04:32 PM</p>

        <div class="user-card">
            <h3>Ariana Brown</h3>
            <p>aBrown@icloud.com</p>
        </div>

        <div class="message-section">
            <h3>Message</h3>
            <p>Just wanted to say that the recent dashboard is amazing! The monthly overview
                cards are exactly what I needed. Keep it up team!
            </p>
        </div>
        </div>
    </section>
  </section>
</main>





        
            

