<!-- Using this to reuse the same admin sidebar on every page ★ -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);

function isActive($pageName) {
    global $currentPage;
    return ($currentPage === $pageName) ? 'active' : '';
}
?>

<!-- Side bar Nav -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h1 class="logo">Budget<span>Lens</span></h1>
        <p class="username">
        Admin: <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
        </p>
    </div>

    <nav class="sidebar-nav">
        <a href="admin_dashboard.php" class="nav-item <?php echo isActive('admin_dashboard'); ?>">
            <span class="nav-icon"> 🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="admin_users.php" class="nav-item <?php echo isActive('admin_users.php'); ?>">
            <span class="nav-icon"> 👥</span>
            <span>Users</span>
        </a>
        
        <!-- (may axe due to time constraints)
        <a href="admin_list.php" class="nav-item <?php echo isActive('admin_lists.php'); ?>">
            <span class="nav-icon"> 📋</span>
            <span>List</span>
        </a>
        -->

        <a href="admin_reports.php" class="nav-item <?php echo isActive('admin_reports.php'); ?>">
            <span class="nav-icon"> 🚨</span>
            <span>Reports</span>
        </a>

        <a href="admin_categories.php" class="nav-item <?php echo isActive('admin_categories.php'); ?>">
            <span class="nav-icon"> 🏷️</span>
            <span>Categories</span>
        </a>
       <!-- (may axe due to time constraints)
        <a href="admin_logs.php" class="nav-item <!<?php echo isActive('admin_logs.php'); ?>">
            <span class="nav-icon"> 📊</span>
            <span>Audit Logs</span>
        </a>
        -->
    
        <!-- Sign out leads back to landing page ★ -->
        <a href="logout.php" class="nav-item">
            <span class="nav-icon">🚶</span>
            <span>Sign Out</span>
        </a>
    </nav>
</aside>