<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Dashboard/Home</title>
    <link rel="stylesheet" href="Home.css">
</head>
<body>

    <div class="dashboard-layout">

        <!-- Sidebar --> 
        <aside class="sidebar">
            <div class="sidebar-header">
              <h1 class="logo">Budget<span>Lens</span></h1>
              <p> Welcome back <?php echo $_SESSION['username']; ?>!</p>
            </div>

            <!-- The actual sidebar navigation -->
            <nav class="sidebar-nav">
                <a href="Home.php" class="nav-item active">
                    <span class="nav-icon">🏠</span>
                    <span>Home</span>
                </a>
                <a href="MyList.php" class="nav-item active">
                    <span class="nav-icon">≣</span>
                    <span>My List</span>
                </a>
                <a href="MyBudget.php" class="nav-item active">
                    <span class="nav-icon">$</span>
                    <span>My Budget</span>
                </a>
                <a href="ScanReceipt.php" class="nav-item active">
                    <span class="nav-icon">📷</span>
                    <span>Scan Receipt</span>
                </a>
                <a href="Settings.php" class="nav-item active">
                    <span class="nav-icon">⚙️</span>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!--Main Content-->
        <main class="main-content">
            <header class="dashboard">
                <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
                <!-- I want to see if we can use real date/time that would sync -->
                <p>Here's your budget overview for April 2026</p>
            </header>

        <!-- Spending Status -->
         <section class="top-cards">
            <div class="card status-card">
                <h3>Spending Status</h3>
                
                <div class="status-display">
                    <span class="status-text">Bad</span>
                    <span class="warning-circle">!</span>
                </div>
                <p class="status-note">You've exceeded 2 budget categories this month.</p>
            </div>

            <!-- Overview card -->
            <div class="card overview-card">
                <h3>Monthly Overview</h3>

                <p class="overview-label">Total spent this month</p>

                <div class="overview-amount-line">
                    <span class="overview-amount">$900.51</span>
                    <span class="overview-budget-text">of $2,000.00 budget</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>

                <div class="progress-meta">
                    <span>45% used</span>
                    <span>$1,099.49 remaining</span>
                </div>
            </div>
     </div>
     </section>

     <!-- Ugent Items-->
      <section class="card urgent-card">
        <h3>Urgent Items</h3>

        <div class="item-row">
        <div class="item-info">
            <h4>Cheese</h4>
            <p>Family shopping list</p>
        </div>
        <button class="item-btn">Add to cart</button>
        </div>

        <div class="item-row">
        <div class="item-info">
            <h4>Multivitamins</h4>
            <p>My Medicine</p>
        </div>
        <button class="item-btn">Add to cart</button>
        </div>

        <a href="MyList.php" class="view-all-link">View all items →</a>
      </section>
</body>
</html>