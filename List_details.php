<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - List details</title>
    <link rel="stylesheet" href="List_details.css">
</head>
<body>
    <div class="dashboard-layout">

    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php';?>

    <main class="main-content">
        <div class="page-header">
            <h1>My List</h1>
        </div>

    <!-- Breadcrumb nav to provide a paper for the list when you click on them ★ -->
    <div class="breadcrumb"> 
         <a href="MyList.php">My List</a>
         <span>&gt;</span>
         <span class="current-list">Foods</span>
    </div>
     
    <!-- Heading ★ -->
    <div class="list-header">
        <div class="list-title-section">
        <a href="mylist.php" class="back-arrow">←</a>
        <h1>Food</h1>
        <span class="shared-badge">Shared</span>
    </div>

    <div class="header-actions">
        <a href="#" class="action-btn secondary">Share</a>
        <a href="#" class="action-btn primary">Add Item +</a>
        <a href="#" class="action-btn danger-outline">Delete List</a>
    </div>
</div>

    <!-- List Card ★ -->
    <section class="list-card">
    
    <!-- Item Rows ★ -->
    <div class="list-item">
                    <div class="item-left">
                        <input type="checkbox">
                        <div class="item-info">
                            <h3>Cheese</h3>
                            <p>Category: Food · Est. $5.99</p>
                        </div>
                    </div>
                    
                    <div class="item-actions">
                        <a href="#" class="small-btn edit-btn">Edit</a>
                        <a href="#" class="small-btn delete-btn">Delete</a>
                    </div>
                </div>

    <div class="list-item">
                    <div class="item-left">
                        <input type="checkbox">
                        <div class="item-info">
                            <h3>Bread</h3>
                            <p>Category: Food · Est. $3.99</p>
                        </div>
                    </div>
                    
                    <div class="item-actions">
                        <a href="#" class="small-btn edit-btn">Edit</a>
                        <a href="#" class="small-btn delete-btn">Delete</a>
                    </div>
                </div>

    <!-- Completed Item ★ -->
    <div class="list-item completed">
                    <div class="item-left">
                        <input type="checkbox" checked>
                        <div class="item-info">
                            <h3>Oat Milk</h3>
                            <p>Category: Food · Est. $4.25</p>
                        </div>
                    </div>

                    <div class="item-actions">
                        <a href="#" class="small-btn edit-btn">Edit</a>
                        <a href="#" class="small-btn delete-btn">Delete</a>
                    </div>
                </div>
    </section>
    </main>
    </div>
    </body>
</html>
