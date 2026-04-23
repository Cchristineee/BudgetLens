<!-- Using this to reuse the same sidebar on every page -->
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
        <p class="username">Welcome back <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</p>
    </div>

    <nav class="sidebar-nav">
        <a href="Home.php" class="nav-item <?php echo isActive('Home.php'); ?>">
            <span class="nav-icon"> 🏠</span>
            <span>Home</span>
        </a>

        <a href="MyList.php" class="nav-item <?php echo isActive('MyList.php'); ?>">
            <span class="nav-icon"> ≣</span>
            <span>My List</span>
        </a>

        <a href="MyBudget.php" class="nav-item <?php echo isActive('MyBudget.php'); ?>">
            <span class="nav-icon"> $</span>
            <span>My Budget</span>
        </a>

        <a href="ScanReceipt.php" class="nav-item <?php echo isActive('ScanReceipt.php'); ?>">
            <span class="nav-icon"> 📷</span>
            <span>Scan Receipt</span>
        </a>

        <a href="Settings.php" class="nav-item <?php echo isActive('Settings.php'); ?>">
            <span class="nav-icon"> ⚙️</span>
            <span>Settings</span>
        </a>
        
        <!-- Sign out leads back to landing page ★ -->
        <a href="LandingPage.html" class="nav-item <?php echo isActive('Settings.php'); ?>">
            <span class="nav-icon"> 🚶</span>
            <span>Sign Out</span>
        </a>
    </nav>
</aside>