<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Settings</title>
    <link rel="stylesheet" href="Settings.css">
</head>
<body>
      <div class="dashboard-layout">
    
    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php'; ?>

     <main class="main-content">
            <h1>Settings</h1>

            <!-- Settings Cards ★  -->

             <!-- Account ★  -->
            <section class="settings-card">
                <h2>ACCOUNT</h2>

                <div class="setting-row">
                    <span>Username</span>
                    <span class="username">@<?php echo $username; ?></span>
                </div>

                <div class="setting-row">
                    <div class="setting-label">Change Password</div>
                    <a href="change_password.html" class="small-btn">Change →</a>
                </div>
        </section>

                 <!-- Support ★  -->
            <section class="settings-card">
                <h2>SUPPORT</h2>

                <div class="setting-row">
                    <div class="setting-label">Make a Report / Report Issue</div>
                    <a href="report_issue.php" class="small-btn">Open →</a> <!-- Note to self: create report_issue.php file ★ -->
                    </div>
                </div>
        </section>

             <!-- These I commented out cause we're probably not going to implement it
            Notifications ★  
            <section class="settings-card">
                <h2>NOTIFICATIONS</h2>

                <div class="setting-row">
                    <span>Budget limit warnings</span>
                    
                    <label class="switch">
                        <input type="checkbox" name="budget_warning">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-row no-border">
                    <span>Shared list warnings</span>

                    <label class="switch">
                        <input type="checkbox" name="shared_warning">
                        <span class="slider"></span>
                    </label>
                </div>
            </section> -->
    </main>
</div>
</body>
</html>