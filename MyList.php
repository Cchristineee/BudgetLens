<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - My Lists</title>
    <link rel="stylesheet" href="MyList.css">
</head>
<body>
    <div class="dashboard-layout">
    
    <?php include 'sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1>My Lists</h1>
            <a href="#" class="new-list-btn">+ New List</a>
        </div>

        <!-- Example list cards - in the real application, these would be generated dynamically from the database -->
        <section class='lists-section'>
            <a href="#" class='list-card'>
                <div class='list-left'>
                    <h2>Sports Equipment</h2>
                </div>

                <div class='list-right'>
                    <span class='item-count'>6 items</span>
                </div>
            </a>

              <a href="#" class='list-card'>
                <div class='list-left'>
                    <h2>My Medicine</h2>
                </div>

                <div class='list-right'>
                    <span class='item-count'>3 items</span>
                </div>
            </a>

              <a href="#" class='list-card'>
                <div class='list-left'>
                    <h2>Family Shopping List</h2>
                </div>

                <div class='list-right'>
                    <span class='shared-badge'>Shared</span>
                    <span class='item-count'>8 items</span>
                </div>
            </a>
        </section>
    </main>
</body>
</html>