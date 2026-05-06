<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Categories</title>
    <link rel="stylesheet" href="admin_categories.css">
</head>
<body>
    <div class="dashboard-layout">
        
    <!-- Admin Sidebar Nav ★ -->
    <?php include 'admin_sidebar.php'; ?>

    <!-- Main content ★ -->
    <main class="admin-categories">
        <h1>All Categories</h1>
    
    <!-- Admin could add a new category to existing shopping list ★ -->
    <section class="card">
        <h2>Add New Category</h2>

        <form class="category-form">
            <input type="text" placeholder="Enter a category name...." name="categoryName"
            />

            <button type="submit">
                <span>+</span>
                Add
            </button>
        </form>
    </section>

    <!-- Admin could view all categories ★ -->
    <!-- Hardcoded......★ -->
    <section class="card">
        <h2>All Categories</h2>

        <div class="category-item">Food</div>
        <div class="category-item">Clothing</div>
        <div class="category-item">Cleaning</div>
        <div class="category-item">Health</div>
        <div class="category-item">Entertainment</div>
      </div>
    </section>
    </main>

    </div>
</body>
</html>

    
    