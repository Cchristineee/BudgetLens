<?php
session_start();
include "connect.php"; //  This connects to your database ❤

//  Get the User ID of person signed in  ❤
$uID = $_SESSION['user_id'] ?? null;
$result = null;

// Get all shopping list that belongs to user (database) ❤
if ($uID) {

    $sql = "SELECT sl.listID, sl.list_name 
            FROM Shopping_List sl
            JOIN User_Shopping_List usl ON sl.listID = usl.listID
            WHERE usl.userID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();
}
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
    
    <?php include 'sidebar.php';?>
    
    <main class="main-content">
        <div class="page-header">
            <h1>My Lists</h1>

            <!-- changed to be a button ❤ -->
            <button id = "new-list-btn"class="new-list-btn">+ New List</button>
        </div>

        <!--Popup when you click list ❤ -->
        <div id="popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 20px; width: 40%; min-height: 20%; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
            <h3 style="margin-top: 0;">Create a new list</h3>
            
            <input type="text" id="listName" placeholder="Enter list name" required style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-size: 16px;">
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="save()" style="flex: 1; padding: 8px; background: #6e9cb3; color: white; border: none; cursor: pointer;">Create</button>
                <button onclick="closePopup()" style="flex: 1; padding: 8px; background: grey; color: white; border: none; cursor: pointer;">Cancel</button>
            </div>
        </div>
        </div>

        <!-- So you can see all of user's shopping list ❤-->
         <section class ="lists-section">
        <?php 
            if ($uID && $result && $result->num_rows > 0) {
                //So you can see all users list
                while($row = $result->fetch_assoc()) {
                    
                    echo "<a href='list_details.php?id=" . $row['listID'] . "' class='list-card'>";
                    echo "<div class='list-left'>";
                    echo "<h2>" . htmlspecialchars($row['list_name']) . "</h2>";
                    echo "</div>";
                    echo "</a>";
                }
            } 
            ?>
            </section>
        <!-- scripts ❤ -->
        <script>
        const popup = document.getElementById('popup');
        const input = document.getElementById('listName');

        document.getElementById('new-list-btn').onclick = () => popup.style.display = 'flex';

        function closePopup() {
            popup.style.display = 'none';
            input.value = '';
        }

        async function save() {
            const listName = input.value;
            if (!listName) {
                alert("Please enter a name");
                return;
            }

            try {
                // Sending data to PHP
                const response = await fetch('create_list.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ list_name: listName })
                });

                // Check if the server actually returned a OK status (check) ❤
        if (!response.ok) {
            throw new Error(`Server error: ${response.status}`);
        }

                // test
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
            }catch (error) {
        alert("Error occured");
            }
        }
    </script>


        <!-- Example list cards - in the real application, these would be generated dynamically from the database 
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
        ❤ -->
        </section>
    </main>
</body>
</html>