<?php
session_start();
include "connect.php";

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit a report.");
}

$userID = $_SESSION['user_id'];

// Get form data
$issue_type = $_POST['issue_type'] ?? '';
$subject = $_POST['subject'] ?? '';
$description = $_POST['description'] ?? '';

// Basic validation
if (empty($issue_type) || empty($subject) || empty($description)) {
    die("All fields are required.");
}

// Start transaction (important for consistency)
$conn->begin_transaction();

try {
    // 1. Insert into user_issue_report
    $stmt1 = $conn->prepare("
        INSERT INTO user_issue_report (report, userID, issue_type, subject)
        VALUES (?, ?, ?, ?)
    ");
    $stmt1->bind_param("siss", $description, $userID, $issue_type, $subject);
    $stmt1->execute();

    // Get the generated reportID
    $reportID = $conn->insert_id;

    // 2. Insert into admin_issue_report
    $stmt2 = $conn->prepare("
        INSERT INTO admin_issue_report (reportID)
        VALUES (?)
    ");
    $stmt2->bind_param("i", $reportID);
    $stmt2->execute();

    // Commit both inserts
    $conn->commit();

    // Redirect
    header("Location: report_issue.php?success=1");
    exit();

} catch (Exception $e) {
    // Rollback if anything fails
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>