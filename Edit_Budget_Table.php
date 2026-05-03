<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "connect.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //setting variables to update attributes ❤
    $oldBudgetLimit = $_POST['oldBudgetLimit'] ?? 0;
    $budgetID = $_POST['BudgetID'] ?? null;
    $categoryID = $_POST['categoryID'] ?? null;
    $Budget_frequencyID = $_POST['BudgetFrequencyID'] ?? 0;
    $oldRemainingAmount = $_POST['remainingLeft'] ?? 0;

    $budgetLimit = str_replace(['$', ','], '', $_POST['budgetLimit'] ?? 0);
    $budgetLimit = (float)$budgetLimit;

    //Calculation for Remaining amount 
    //Ex. if oldBudget Limit is 100 and oldRemaining amount is 70 (spent 30),
    //And you changed limit to 200, Remaining amount should be 170 (spent 30) ❤
    $remainingLeft = ($budgetLimit - $oldBudgetLimit)+ $oldRemainingAmount;

    if (!$budgetID) {
        die("Missing budgetID");
    }

    //  Update the database ❤
    $stmt = $conn->prepare("
        UPDATE Budget
        SET budgetLimit = ?, 
            categoryID = ?, 
            remaining_amount_left = ?, 
            Budget_frequencyID = ?
        WHERE budgetID = ?
    ");

    $stmt->bind_param("didii", $budgetLimit, $categoryID,$remainingLeft, $Budget_frequencyID, $budgetID);

    if ($stmt->execute()) {
        echo "Budget updated successfully";
        header('location:MyBudget.php');
        exit();

    } else {
        echo "Error updating budget: " . $stmt->error;
    }
}
?>