<?php
session_start();
include "connect.php";

/* ❤ FILTER VALUES FROM DROPDOWNS */
$statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
$categoryFilter = isset($_GET['category']) && $_GET['category'] !== '' ? $_GET['category'] : null;

// Get all reports + status + name  ❤
$query = " SELECT r.reportID, r.subject, r.report, r.issue_type, r.userID, a.is_completed,u.username 
FROM user_issue_report r 
LEFT JOIN admin_issue_report a ON r.reportID = a.reportID
LEFT JOIN user_data u ON r.userID = u.userID
WHERE 1=1
";

/* ❤ APPLY STATUS FILTER */
if ($statusFilter !== null) {
    $query .= " AND a.is_completed = " . intval($statusFilter);
}

/* ❤ APPLY CATEGORY FILTER */
if ($categoryFilter !== null) {
    $query .= " AND LOWER(r.issue_type) = '" . $conn->real_escape_string($categoryFilter) . "'";
}

$query .= " ORDER BY r.reportID DESC";

$result = $conn->query($query); 
$reports = []; while ($row = $result->fetch_assoc()) { $reports[] = $row; }

//Get selected report  ❤
$selectedReport = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    foreach ($reports as $r) {
        if ($r['reportID'] == $id) {
            $selectedReport = $r;
            break;
        }
    }
}
// default will be first in array ❤
if (!$selectedReport && count($reports) > 0) {
    $selectedReport = $reports[0];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Reports</title>
    <link rel="stylesheet" href="admin_reports.css?v=1">
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

    <!-- FILTERING SYSTEM ❤ -->
    <form method="GET" class="filters">
        <div class="filter-row">
            <select name="status" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="0" <?php if(isset($_GET['status']) && $_GET['status']=="0") echo "selected"; ?>>Active</option>
                <option value="1" <?php if(isset($_GET['status']) && $_GET['status']=="1") echo "selected"; ?>>Read</option>
            </select>

            <select name="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <option value="bug" <?php if(isset($_GET['category']) && $_GET['category']=="bug") echo "selected"; ?>>Bug</option>
                <option value="data" <?php if(isset($_GET['category']) && $_GET['category']=="data") echo "selected"; ?>>Data Issue</option>
                <option value="feature" <?php if(isset($_GET['category']) && $_GET['category']=="feature") echo "selected"; ?>>Feature Request</option>
                <option value="feedback" <?php if(isset($_GET['category']) && $_GET['category']=="feedback") echo "selected"; ?>>Feedback</option>
                <option value="other" <?php if(isset($_GET['category']) && $_GET['category']=="other") echo "selected"; ?>>Other</option>
            </select>
        </div>
    </form>

    <?php foreach ($reports as $report): ?>
        <a href="?id=<?php echo $report['reportID']; ?>" style="text-decoration:none; color:inherit;">
            <div class="report-card <?php echo ($report['is_completed'] == 0) ? 'active' : ''; ?>">
                
                <div>
                    <!-- Figure out which issue_type the report is to give correct color scheme ❤ -->
                    <?php
                    $type = strtolower(trim($report['issue_type']));

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
                        echo '<span class="bug">☼</span>';
                    }
                    ?>

                    <span class="pill <?php echo $type; ?>">
                        <?php echo htmlspecialchars($report['issue_type']); ?>
                    </span>
                    <!-- Identify if report is active or not ❤ -->
                    <?php if ($report['is_completed'] == 0): ?>
                        <span class="pill new">Active</span>
                    <?php else: ?>
                        <span class="pill read">Read</span>
                    <?php endif; ?>
                </div>
                    <!-- Output Report Info from User ❤ -->
                <h3><?php echo htmlspecialchars($report['subject']); ?></h3>
                <p>User ID: <?php echo $report['userID']; ?></p>
                <small>Report #<?php echo $report['reportID']; ?></small>

            </div>
        </a>
    <?php endforeach; ?>
</aside>

        <!-- Right Pannel ★ -->  
        <section class="report-detail-panel">

    <?php if ($selectedReport): 
    $type = strtolower(trim($selectedReport['issue_type']));
    ?>

    <div class="detail-top">
        <div class="detail-tags">
        <!-- Identify the  issue_type and gives correct color associated ❤ -->  
            <?php
            if ($type === 'feature') {
                echo '<span class="feature">▱</span>';
            } elseif ($type === 'feedback') {
                echo '<span class="feedback">▱</span>';
            } elseif ($type === 'bug') {
                echo '<span class="bug">☼</span>';
            } elseif ($type === 'other') {
                echo '<span class="other">☼</span>';
            } elseif ($type === 'data') {
                echo '<span class="data">☼</span>';
            } else {
                echo '<span class="bug">☼</span>';
            }
            ?>
     <!-- Retrieve Selected report  ❤-->  
            <span class="pill <?php echo $type; ?>">
                <?php echo htmlspecialchars($selectedReport['issue_type']); ?>
            </span>
            <!-- If completed == 0 then Pill says Active else Read ❤-->
            <span class="pill <?php echo ($selectedReport['is_completed'] == 0) ? 'new' : 'read'; ?>">
                <?php echo ($selectedReport['is_completed'] == 0) ? 'Active' : 'Read'; ?>
            </span>

        </div>

        <button class="archive-btn">
            <?php echo ($selectedReport['is_completed'] == 0) ? 'Set as Read' : 'Mark Active'; ?>
        </button>
    </div>
     <!-- Subject ❤-->  
    <h2><?php echo htmlspecialchars($selectedReport['subject']); ?></h2>
    <!-- ReportID ❤-->  
    <p class="date">Report #<?php echo $selectedReport['reportID']; ?></p>
     <!--   User Info ❤-->  
    <div class="user-card">
        <h3>Username: <?php echo htmlspecialchars($selectedReport['username']); ?></h3> 
        <h3>User ID: <?php echo $selectedReport['userID']; ?></h3>
    </div>
    <!-- Message content ❤-->  
    <div class="message-section">
        <h3>Message</h3>
        <p><?php echo nl2br(htmlspecialchars($selectedReport['report'])); ?></p>
    </div>

<?php else: ?>
     <!-- if no report was selected print out this statement ❤-->  
    <p>No report selected</p>
<?php endif; ?>

</section>
  </section>
</main>
