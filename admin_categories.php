<?php
session_start();
include "connect.php";
 
// get category name ❤
$query = "SELECT global_categoryID, name FROM Global_Category ORDER BY name ASC";
$result = $conn->query($query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["categoryName"])) {
    $categoryName = trim($_POST["categoryName"]);

    // Prevent duplicates (optional but recommended)
    $check = $conn->prepare("SELECT global_categoryID FROM Global_Category WHERE name = ?");
    $check->bind_param("s", $categoryName);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<p style='color:red;'>Category already exists.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO Global_Category (name) VALUES (?)");
        $stmt->bind_param("s", $categoryName);

        if ($stmt->execute()) {
            // Redirect to prevent resubmission on refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p style='color:red;'>Error adding category.</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Admin Categories</title>
    <link rel="stylesheet" href="admin_categories.css?v=1">
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

        <form class="category-form" method="POST" action="">
    <input 
        type="text" 
        name="categoryName" 
        placeholder="Enter a category name...." 
        required
    />

    <button type="submit">
        <span>+</span>
        Add
    </button>
    </form>
    </section>

    <!-- Admin could view all categories ★ -->
        <section class="card">
         <h2>All Categories</h2>
    <!-- Category population ❤ -->
         <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="category-item">
                <?php echo htmlspecialchars($row['name']); ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No categories found.</p>
    <?php endif; ?>
</section>
      </div>
    </section>
    </main>

    </div>
</body>
</html>

    
    