<?php
session_start();
include "connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUdgetLens - Report an Issue</title>
    <link rel="stylesheet" href="report_issue.css">
</head>
<body>

    <div class="dashboard-layout">
    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php';?>

    <main class="main-content">
        <div class="page-header">
            <h1>Report an Issue</h1>
            <p>Let us know what's going wrong or how we can improve.</p>
        </div>

        <!-- Success Message when report is sent ❤ -->
        <?php if (isset($_GET['success'])): ?>
    <p style="color: green; margin-bottom: 15px;">
        Success! Your message has been sent to the admin.
    </p>
    <?php endif; ?>
    
        <form action="submit_issue.php" method="POST" enctype="multipart/form-data"> <!-- have to make submit page ★ -->

        <label>Issue Type</label>
        <select name="issue_type" required>
            <option value="">Select an option</option>
                <option value="bug">Bug</option>
                <option value="data">Data Issue</option>
                <option value="feature">Feature Request</option>
                <option value="other">Other</option>
        </select>

        <label>Subject</label>
        <input type="text" name="subject" required>

        <label>Description</label>
        <textarea name="description" rows="5" required></textarea>

        <!-- Feature currently not available will do if we have time

        <label>Upload Screenshot (optional)</label>
        <input type="file" name="screenshot"> -->
        
        <button type="submit">Submit Report</button>
        
        </form>
    </main>
</div>


</body>
</html>