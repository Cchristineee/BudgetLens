<?php 
session_start();
include "connect.php";

//gets categories for dropdown ❤
$query = "SELECT global_categoryID, name FROM global_category ORDER BY name ASC";
$result = $conn->query($query);


$listID = $_GET['id'] ?? null;

if (!$listID) {
    echo "No list selected.";
    exit;
}
// i is int 
$stmt = $conn->prepare("SELECT list_name FROM Shopping_List WHERE listID = ?");
$stmt->bind_param("i", $listID);
$stmt->execute();
// used to get current list name ❤
$listInfo = $stmt->get_result()->fetch_assoc();
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
         <!-- shows current list ❤ --> 
         <span class="current-list"> <?php echo htmlspecialchars($listInfo['list_name'] ?? 'Unknown'); ?> </span>
    </div>
     
    <!-- Heading ★❤ -->
   <div class="list-header"> 
        <div class="list-title-section">
        <a href="mylist.php" class="back-arrow">←</a>
        <h1><?php echo htmlspecialchars($listInfo['list_name'] ?? 'Unknown'); ?></h1>
        
   </div>

    <div class="header-actions">
        <span class="shared-badge">Shared</span>
        <a href="#" class="action-btn secondary">Share</a>
        <a href="#" id ="addItem" class="action-btn primary">Add Item +</a>
        <a href="#" onclick="deleteList()"class="action-btn danger-outline">Delete List</a>
    </div>
</div>



    <!-- List Card ★ -->
    <section class="list-card">
    
    <!-- Item Rows ★❤ -->

    <?php
        //  Added itemID to the SELECT list ❤
        $itemStmt = $conn->prepare("SELECT item.itemID, item.item_name, item.item_price,Global_Category.name FROM item Left Join Global_Category 
        ON item.categoryID = global_category.global_categoryID WHERE listID = ?");
        $itemStmt->bind_param("i", $listID);
        $itemStmt->execute();
        $items = $itemStmt->get_result();

        while ($row = $items->fetch_assoc()) {
            ?>
        
        <!-- showing items in the list ❤ -->
                     <div class="list-item">
                    <div class="item-left">
                    <input type="checkbox" onchange="toggleComplete(this)">
                        <div class="item-info">
                        <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                        <p>Category: <?php echo htmlspecialchars($row['name']); ?> · Est. $<?php echo number_format($row['item_price'], 2); ?></p>
                        </div> 
                        </div>
                    
                        <div class="item-actions">
                        <a href="#" class="small-btn edit-btn">Edit</a>
                        <button class ="small-btn delete-btn" onclick="deleteItem(<?php echo $row['itemID']; ?>)">Purchase </button>
                        </div>
                        </div>
                <?php
                 }
        ?>

        
    </section>



    <!--Popup when you click list ❤ -->
    <div id="itemPopup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
        <div style="background: white; padding: 40px; border-radius: 20px; width: 50%; min-height: 27%; display: flex; flex-direction: column; gap 105px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);font size:55px;">
        <h3 style="text-align: center;">Add Item</h3>
            
            <input type="text" id="itemName" placeholder="Item Name" required style="width: 90%; padding: 8px; margin-bottom: 15px;">
            <input type="number" id="itemPrice" step="0.01" placeholder="Price (default 0)" style="width: 90%; padding: 8px; margin-bottom: 15px;">
            
            <select id="itemCategory" name="categoryID" required style="width: 96%; padding: 8px; margin-bottom: 15px;">
                <option value="">-- Select Category --</option>
                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['global_categoryID']; ?>">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="saveItem()" style="flex: 1; padding: 8px; background: #6e9cb3; color: white; border: none; cursor: pointer;">Create</button>
                <button onclick="closeItemPopup()" style="flex: 1; padding: 8px; background: grey; color: white; border: none; cursor: pointer;">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const itemPopup = document.getElementById('itemPopup');
        const listID = <?php echo json_encode($listID); ?>;

        document.getElementById('addItem').onclick = () => itemPopup.style.display = 'flex';

        //popup ❤
        function closeItemPopup() { 
            itemPopup.style.display = 'none'; 
            document.getElementById('itemName').value = '';
            document.getElementById('itemPrice').value = '';
        }

        //saving item ❤
        async function saveItem() {
            const name = document.getElementById('itemName').value;
            const priceInput = document.getElementById('itemPrice').value;
            const categoryID = document.getElementById('itemCategory').value;
            if (!name) { 
                alert("Enter item name"); 
                return; 
            }

            // Check if category is selected ❤
             if (!categoryID) {
             alert("Please select a category");
            return;
            
        }
            // If priceInput is an empty string, we set it to 0 ❤
            const finalPrice = priceInput === "" ? 0 : priceInput;

            //adding item to database ❤
            try {
                const response = await fetch('add_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        item_name: name, 
                        item_price: finalPrice,
                        list_id: listID, 
                        categoryID: categoryID
                    })
                });

                const result = await response.json();
                if (result.status === "success") {
                    location.reload();
                } else {
                    alert("Error: " + result.message);
                }
            } catch (error) {
                alert("Error occurred: Could not connect to the server.");
            }
        }

        //delete item ❤
        async function deleteItem(id) {
            if (!confirm("Are you sure you want to remove this item?")) return;
        
            try {
                const response = await fetch('delete_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ item_id: id })
                });
        
                const result = await response.json();
                if (result.status === "success") {
                    location.reload();
                } else {
                    alert("Delete failed: " + result.message);
                }
            } catch (error) {
                alert("Error connecting to server.");
            }
        }

        //delete list ❤
        async function deleteList() {

    // CONFIRMATIONS DONT DELETE PLZZZZ WE CANT RECOVER IT 

    const firstConfirm = confirm("Are you sure you want to delete this ENTIRE list and all its items?");
    if (!firstConfirm) return;

    const secondConfirm = confirm("When you delete it can not be retrieved back");
    if (!secondConfirm) return;

    
    try {
        const response = await fetch('delete_list.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ list_id: listID }) 
        });

        const result = await response.json();
        if (result.status === "success") {
            alert("List deleted successfully.");

            // Redirect back to the MyList Page ❤
            window.location.href = 'myList.php'; 
        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        alert("Could not connect to the server.");
    }
}
    </script>
<!-- toggle checkbox -->
<script>
function toggleComplete(checkbox) {
    const listItem = checkbox.closest('.list-item');

    if (checkbox.checked) {
        listItem.classList.add('completed');
    } else {
        listItem.classList.remove('completed');
    }
}
</script>


    </main>
    </div>

    </body>
</html>
