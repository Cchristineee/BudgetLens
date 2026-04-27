<?php
session_start();
include "connect.php";

$uID = $_SESSION['user_id'] ?? null;
$budgetResult = null;

if ($uID) {
    // mathcing budget.categoryID with global_category.global_categoryID ❤
    $sql = "SELECT global_category.name, budget.budgetLimit, budget.remaining_amount_left 
            FROM budget 
            JOIN global_category ON budget.categoryID = global_category.global_categoryID
            WHERE budget.userID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $budgetResult = $stmt->get_result();

   // Calculation for Overview at top 
   
   $summarySql = "SELECT 
   SUM(budgetLimit) AS totalLimit,
   SUM(budgetLimit - remaining_amount_left) AS totalSpent
    FROM budget
    WHERE userID = ?";

$summaryStmt = $conn->prepare($summarySql);
$summaryStmt->bind_param("i", $uID);
$summaryStmt->execute();
$summaryResult = $summaryStmt->get_result();

if ($row = $summaryResult->fetch_assoc()) {
$summaryLimit = $row['totalLimit'] ?? 0;
$summarySpent = $row['totalSpent'] ?? 0;
}


    // gets categories that users have not chosen yet to put into dropdown ❤

    $catSql = "SELECT global_categoryID, name FROM global_category 
               WHERE global_categoryID NOT IN (SELECT categoryID FROM budget WHERE userID = ?)";
    $catStmt = $conn->prepare($catSql);
    $catStmt->bind_param("i", $uID);
    $catStmt->execute();
    $availableCategories = $catStmt->get_result(); 
}

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetLens - My Budget</title>
     <link rel="stylesheet" href="MyBudget.css">
</head>
<body>
    <div class="dashboard-layout">
    
    <!-- Reusing sidebar throughout ★ -->
    <?php include 'sidebar.php'; ?>

     <main class="main-content">
        <div class="page-header">
            <h1>My Budget</h1>
            <a href="#" id="Budget-button" class="create-budget-btn">+ Create Budget</a>

            <!-- Popup ❤ -->
            <div id="popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; border-radius: 8px; width: 250px; text-align: center;">
        <h3 style="margin-top: 0;">Create a new Budget</h3>
        
        <select id="BudgetName" required style="width: 90%; padding: 8px; margin-bottom: 15px;">
    <option value="" disabled selected>Select a Budget Category</option>

    <?php 
    if ($availableCategories && $availableCategories->num_rows > 0) {
        while($cat = $availableCategories->fetch_assoc()) {
            echo "<option value='" . $cat['global_categoryID'] . "'>" 
                . htmlspecialchars($cat['name']) . "</option>";
        }
    }
    ?>
</select>
          <input type="number" id="SpendingLimit" step="0.01" placeholder="Price (default 0)" style="width: 90%; padding: 8px; margin-bottom: 15px;">
        
        <div style="display: flex; gap: 10px;">
            <button onclick="save()" style="flex: 1; padding: 8px; background: green; color: white; border: none; cursor: pointer;">Create</button>
            <button onclick="closePopup()" style="flex: 1; padding: 8px; background: grey; color: white; border: none; cursor: pointer;">Cancel</button>
        </div>
    </div>
</div>
        </div>

        <!-- Summary Card ★ -->
        <div class="summary-card">
            <p>You currently spent</p>
            <h2>$<?php echo number_format($summarySpent, 2); ?>
            <span> of $<?php echo number_format($summaryLimit, 2); ?></span> </h2>
        </div>
     
        <!-- Grid Container ★ -->
         <div class="budget-grid"> 

        <?php 
if ($uID && $budgetResult && $budgetResult->num_rows > 0) {
    while($row = $budgetResult->fetch_assoc()) {
        
        $remaining = $row['remaining_amount_left'];
        $limit = $row['budgetLimit'];
        $spent = $limit - $remaining;

        // for bar ❤ 
        $percent = ($limit > 0) ? ($spent / $limit) * 100 : 0;
        $percent = min(100, max(0, $percent)); // 

        // Status color class ❤ 
        $statusClass = ($remaining <= 0) ? "#e74c3c" : "#27ae60";
        ?>
 
        <!-- Create Boxes for indivdual budgets ❤  -->
        <a href="Edit_Budget.php" class="budget-card-link"> <!-- makes it clickable to edit budget ★ -->
            <div class="budget-card">
                <h3 class ="card-title"> <?php echo htmlspecialchars($row['name']); ?> </h3>
                <p class="card-text">
                $<?php echo number_format($spent, 2); ?> 
                of $<?php echo number_format($limit, 2); ?> spent
                </p>
                <div class="progress-bar">
                    <div class="progress-fill <?php echo $statusClass; ?>"
                     style="width:<?php echo $percent; ?>%; background: <?php echo $statusColor; ?>;">
                     </div>
                </div>

            </div>
        </a>

<?php 
    }
}
?>
    <!-- script ❤ -->
<script>
    const popup = document.getElementById('popup');
    const dropdown = document.getElementById('BudgetName');
    const spendingInput = document.getElementById('SpendingLimit');

    document.getElementById('Budget-button').onclick = () => popup.style.display = 'flex';

    function closePopup() {
        popup.style.display = 'none';
        spendingInput.value = ''; 
    }

 
    async function save() {

        const dropdown = document.getElementById("BudgetName");
    const categoryID = dropdown.value; 
    const limit = document.getElementById("SpendingLimit").value;

        if (!categoryID) {
        alert("Please select a budget category");
        return;
    }



        try {
            // Sending data to PHP ❤
            const response = await fetch('Creating_Budget.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                                        categoryID: categoryID,
                                        budgetLimit: parseFloat(limit),
                                        remainingAmount: parseFloat(limit), //will be the same as budgetLimit when first created 
                                        frequencyID: 3 }) //Hardcoded for now
            });


// test to make sure that json file is being sent ❤
            const text = await response.text();
            console.log("RAW RESPONSE:", text);

            let result;
            try {
            result = JSON.parse(text);
            } catch (e) {
            console.error("Invalid JSON:", text);
            alert("Server did not return valid JSON");
            return;
            }

            if (result.status === "success") {
                alert("List created successfully in database!");

                window.location.reload();

                closePopup();
            } else {
                alert("Error occurred: " + (result.message || "Unknown error"));
            }
        } catch (error) {
            alert("Error occurred: Could not connect to the server.");
        }
    }
</script>
</main>
</body>
</html>