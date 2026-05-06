<?php
session_start();
include "connect.php";
// Get all users alphebetical ❤
$users = $conn->query("SELECT userID, username FROM user_data ORDER BY username ASC");
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
        <span>LISTS</span>
        <span>Item</span>
        <span>REPORTS</span>
        <span>ACTIONS</span>
    </div>

    <?php while ($user = $users->fetch_assoc()): ?>

        <?php
        $userID = $user['userID'];

        //  Count shopping lists ❤
        $stmt1 = $conn->prepare("SELECT COUNT(*) FROM User_Shopping_List WHERE userID = ?");
        $stmt1->bind_param("i", $userID);
        $stmt1->execute();
        $stmt1->bind_result($listCount);
        $stmt1->fetch();
        $stmt1->close();

        //  Count user issue reports ❤
        $stmt2 = $conn->prepare("SELECT COUNT(*) FROM user_issue_report WHERE userID = ?");
        $stmt2->bind_param("i", $userID);
        $stmt2->execute();
        $stmt2->bind_result($reportCount);
        $stmt2->fetch();
        $stmt2->close();

        // Count active admin reports (is_completed = 0)
        $stmt3 = $conn->prepare("
            SELECT COUNT(*) 
            FROM admin_issue_report ar
            JOIN user_issue_report ur ON ar.reportID = ur.reportID
            WHERE ur.userID = ? AND ar.is_completed = 0
        ");
        $stmt3->bind_param("i", $userID);
        $stmt3->execute();
        $stmt3->bind_result($activeReports);
        $stmt3->fetch();
        $stmt3->close();

        // Count items per user ❤

        $stmt4 = $conn->prepare("
        SELECT COUNT(DISTINCT i.itemID)
        FROM User_Shopping_List l
        LEFT JOIN item i ON l.listID = i.listID
        WHERE l.userID = ?
    ");
    $stmt4->bind_param("i", $userID);
    $stmt4->execute();
    $stmt4->bind_result($itemCount);
    $stmt4->fetch();
    $stmt4->close();
        ?>

        

        <div class="table-row">
            <span><?= htmlspecialchars($user['username']) ?></span>
            <span><?= $listCount ?></span>
            <span><?= $itemCount ?></span>
            <span>
                <?php if ($activeReports > 0): ?>
                    <span class="status active">Active (<?= $activeReports ?>)</span>
                <?php else: ?>
                    <span class="status none">None</span>
                <?php endif; ?>
            </span>

            <span>
                <button class="delete-btn" data-id="<?= $userID ?>">Delete</button>
            </span>
        </div>

    <?php endwhile; ?>

</div>
      </section>
    </main>
</div>
<script>
    // script to delete user goes to deleye_user_admin.php
document.querySelectorAll(".delete-btn").forEach(button => {
    button.addEventListener("click", function() {
        const userID = this.getAttribute("data-id");

        if (!confirm("Are you sure you want to delete this user?")) return;

        fetch("delete_user_admin.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "user_id=" + userID
        })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                location.reload();
            } else {
                alert("Failed to delete user");
            }
        });
    });
});
</script>
</body>
</html>