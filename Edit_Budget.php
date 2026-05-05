<?php
session_start();
include "connect.php";
$uID = $_SESSION['user_id'] ?? null;
$global_categoryID = $_GET['id'] ?? 0;
// used to get current category name ❤ 
$stmt = $conn->prepare("SELECT name FROM Global_Category WHERE global_categoryID = ?");
$stmt->bind_param("i", $global_categoryID);
$stmt->execute();
$categoryInfo = $stmt->get_result()->fetch_assoc();

//getting budgetID ❤ 
$stmt = $conn->prepare("SELECT budgetID FROM Budget WHERE categoryID = ?");
$stmt->bind_param("i", $global_categoryID);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$BudgetID = $row['budgetID'] ?? null;
//getting remaining_amount_left ❤ 
$stmt = $conn->prepare("SELECT remaining_amount_left FROM Budget WHERE categoryID = ?");
$stmt->bind_param("i", $global_categoryID);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$remainingLeft = $row['remaining_amount_left'] ?? null;

//getting budgetLimit ❤ 
$stmt = $conn->prepare("SELECT budgetLimit FROM Budget WHERE categoryID = ?");
$stmt->bind_param("i", $global_categoryID);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$oldBudgetLimit = $row['budgetLimit'] ?? null;

//get transactions for "this month transaction in edit ❤

$transactions = [];

if ($uID && $global_categoryID) {
    $stmt = $conn->prepare("
        SELECT 
            r.date,
            ri.receipt_item_name,
            ri.receipt_item_price
        FROM Receipt r
        JOIN Receipt_item ri ON r.receiptID = ri.receiptID
        WHERE 
            r.userID = ?
            AND ri.categoryID = ?
            AND MONTH(r.date) = MONTH(CURRENT_DATE())
            AND YEAR(r.date) = YEAR(CURRENT_DATE())
        ORDER BY r.date DESC
        LIMIT 10
    ");

    $stmt->bind_param("ii", $uID, $global_categoryID);
    $stmt->execute();
    $transactions = $stmt->get_result();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - Edit Budget</title>
    <link rel="stylesheet" href="Edit_Budget.css">
</head>
<body>

    <div class="dashboard-layout">

    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php';?>

    <main class="main-content">
    <p class="breadcrumb">My Budget &gt; <span><?php echo htmlspecialchars($categoryInfo['name'] ?? 'Unknown'); ?></span></p>

    <div class="edit-header">
        <a href="MyBudget.php" class="back-arrow">←</a>
        <h1>Edit Budget:<?php echo htmlspecialchars($categoryInfo['name'] ?? 'Unknown'); ?> </h1>
    </div>

    
    <form method="POST" action="Edit_Budget_Table.php">
    <div class="edit-card">


        <label>Budget Limit</label>
        <input type="text" name="budgetLimit" placeholder="$300.00">

        <label>Reset Period</label>
        <select name="BudgetFrequencyID">
            <option value = 1>Weekly</option>
            <option value = 2>Bi-Weekly</option>
            <option value = 3>2 Minutes </option>  <!-- Presentation purposes ★ -->
        </select>
        <input type="hidden" name="BudgetID" value="<?= $BudgetID ?>">
        <input type="hidden" name="categoryID" value="<?= $global_categoryID ?>">
        <input type="hidden" name="remainingLeft" value="<?= $remainingLeft ?>">
        <input type="hidden" name="oldBudgetLimit" value="<?= $oldBudgetLimit ?>">
        <hr>

        <h2>Recent Transactions</h2>

        <?php if ($transactions && $transactions->num_rows > 0): ?>
    <?php while ($row = $transactions->fetch_assoc()): ?>
        <div class="transaction-row">
            <div>
                <h4><?= htmlspecialchars($row['receipt_item_name']) ?></h4>
                <p><?= date("M d, Y", strtotime($row['date'])) ?></p>
            </div>
            <strong>-$<?= number_format($row['receipt_item_price'], 2) ?></strong>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No transactions for this month.</p>
<?php endif; ?>

        <div class="button-row">
            <button type="button" class="delete-btn" onclick="deleteBudget(<?= $global_categoryID ?>)"> Delete Budget </button>
            <button class="save-btn" type="submit">Save Changes</button>
        </div>
    </div> 
</form> 

</main>
</div>
<script>
    //script to delete Budget ❤ 
    function deleteBudget(id) {
    if (confirm("Are you sure you want to delete this budget category? This cannot be undone.")) {
        
        fetch('Delete_Budget.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ categoryID: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Budget deleted successfully!");
                window.location.href = 'MyBudget.php'; // Redirect after deletion
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while deleting.");
        });
    }
}
</script>
</body>
</html>