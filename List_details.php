<?php 
session_start();
include "connect.php";

//gets categories for dropdown ❤
$query = "SELECT global_categoryID, name FROM Global_Category ORDER BY name ASC";
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

// for icon pop on if your list is shared 
$stmt = $conn->prepare("
    SELECT is_shared
    FROM User_Shopping_List
    WHERE listID = ?
");
$stmt->bind_param("i", $listID);
$stmt->execute();

$shareInfo = $stmt->get_result()->fetch_assoc();
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
    
    <!-- Heading ★❤ -->
    <div class="header-actions">
    <!-- icon showing if your list is shared-->
    <?php if (($shareInfo['is_shared'] ?? 0) == 1): ?>
    <span class="shared-badge">Shared</span>
    <?php endif; ?>

        <button type="button" class="action-btn" onclick="openShareModal()">Share</button>
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
        ON item.categoryID = Global_Category.global_categoryID WHERE listID = ?");
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
                        <a href="#" 
   class="small-btn edit-btn"
   onclick="openEditPopup(
        <?= $row['itemID'] ?>,
        '<?= htmlspecialchars($row['item_name'], ENT_QUOTES) ?>',
        <?= $row['item_price'] ?>,
        <?= $row['categoryID'] ?>
   )">
   Edit
</a>
                        <button class ="small-btn purchase-btn" onclick="deleteItem(<?php echo $row['itemID']; ?>)">Purchase</button>
                        </div>
                        </div>
                <?php
                 }
        ?>
    </section>

    <!--Popup when you click add item ❤ -->
    <div id="itemPopup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    
    <div style="
        background: white; 
        padding: 35px 40px; 
        border-radius: 20px; 
        width: 420px; 
        display: flex; 
        flex-direction: column; 
        gap: 18px; 
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    ">
        
        <h3 style="text-align: center; font-size: 22px; margin-bottom: 10px;">
            Add Item
        </h3>

        <input type="text" id="itemName" placeholder="Item Name" required 
            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">

        <input type="number" id="itemPrice" step="0.01" placeholder="Price (default 0)" 
            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">

        <select id="itemCategory" name="categoryID" required 
            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
            
            <option value="">-- Select Category --</option>
            <?php while($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['global_categoryID']; ?>">
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <div style="display: flex; gap: 12px; margin-top: 10px;">
            <button onclick="saveItem()" 
                style="flex: 1; padding: 12px; background: #6e9cb3; color: white; border: none; border-radius: 8px; cursor: pointer;">
                Create
            </button>

            <button onclick="closeItemPopup()" 
                style="flex: 1; padding: 12px; background: #777; color: white; border: none; border-radius: 8px; cursor: pointer;">
                Cancel
            </button>
        </div>

    </div>
</div>


<!-- Pop up for edit button -->
<div id="editItemPopup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    
    <div style="
        background: white; 
        padding: 35px 40px; 
        border-radius: 20px; 
        width: 420px; 
        display: flex; 
        flex-direction: column; 
        gap: 18px; 
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    ">
        
        <h3 style="text-align: center; font-size: 22px; margin-bottom: 10px;">
            Edit Item
        </h3>

        <!-- hidden ID for editing -->
        <input type="hidden" id="editItemID">

        <input type="text" id="editItemName" placeholder="Item Name" required 
            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">

        <input type="number" id="editItemPrice" step="0.01" placeholder="Price" 
            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">

        <select id="editItemCategory" name="categoryID" required 
            style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
            
            <option value="">-- Select Category --</option>
            <?php 
            // run the query again OR store results beforehand
            $result2 = $conn->query("SELECT global_categoryID, name FROM global_category ORDER BY name ASC");
            while($row = $result2->fetch_assoc()): ?>
                <option value="<?php echo $row['global_categoryID']; ?>">
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <div style="display: flex; gap: 12px; margin-top: 10px;">
            <button onclick="updateItem()" 
                style="flex: 1; padding: 12px; background: #6e9cb3; color: white; border: none; border-radius: 8px; cursor: pointer;">
                Update
            </button>

            <button onclick="closeEditPopup()" 
                style="flex: 1; padding: 12px; background: #777; color: white; border: none; border-radius: 8px; cursor: pointer;">
                Cancel
            </button>
        </div>

    </div>
</div>

     <!--  Lets the user share budget list with another user by entering thier username★❤ -->
     <div id="shareModal" class="modal-overlay">
    <div class="share-modal">
        <h2>Share "<?php echo htmlspecialchars($listInfo['list_name']); ?>"</h2>

        <div class="info-box">
            System will verify the username exists before sharing.
        </div>

        <form id="shareForm" onsubmit="handleShare(event)">
            
            
            <div id="shareMessage" style="margin-bottom:10px;"></div>

            <input type="hidden" name="list_id" value="<?php echo htmlspecialchars($listID); ?>">

            <label>ENTER USERNAME TO SHARE WITH</label>

            <input 
                type="text" 
                name="share_username" 
                placeholder="Type a BudgetLens username..." 
                required
            >

            <p class="shared-title">Currently shared with:</p>

            <div class="shared-user">
                <span>@John_doe</span>
                <button type="button" class="remove-btn">Remove</button>
            </div>

            <div class="modal-actions">
                <button type="button" class="cancel-btn" onclick="closeShareModal()">Cancel</button>
                <button type="submit" class="save-btn">Share List</button>
            </div>
        </form>
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

        //purchase item ❤
        async function deleteItem(id) {
            if (!confirm("Are you sure you want to purchase this item?")) return;
        
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

<script>
    
//Share Modal functionality ★
function openShareModal() {
        document.getElementById("shareModal").classList.add("active");
        }

function closeShareModal() {
        document.getElementById("shareModal").classList.remove("active");
        }
</script>

<script>
    // So errors pop up in Shared list pop up window ❤

document.addEventListener("DOMContentLoaded", function() {

    const form = document.getElementById("shareForm");

    form.addEventListener("submit", function(e) {
        e.preventDefault(); //  Stops redirection issue ❤

        const formData = new FormData(form);

        fetch("share_list.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            const msgBox = document.getElementById("shareMessage");

            msgBox.textContent = data.message;

            if (data.status === "error") {
                msgBox.style.color = "red";
            } else {
                msgBox.style.color = "green";
                form.reset();
            }
        })
        .catch(() => {
            const msgBox = document.getElementById("shareMessage");
            msgBox.textContent = "Something went wrong.";
            msgBox.style.color = "red";
        });
    });

});
// for edit pop up 
function openEditPopup(id, name, price, categoryID) {
    document.getElementById("editItemPopup").style.display = "flex";

    document.getElementById("editItemID").value = id;
    document.getElementById("editItemName").value = name;
    document.getElementById("editItemPrice").value = price;
    document.getElementById("editItemCategory").value = categoryID;
}
</script>



    </main>
    </div>
    </body>
</html>
