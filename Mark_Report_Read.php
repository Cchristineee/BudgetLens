<?php
session_start();
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'])) {

    $reportID = intval($_POST['report_id']);

    $stmt = $conn->prepare("
        UPDATE admin_issue_report
        SET is_completed = 1
        WHERE reportID = ?
    ");

    $stmt->bind_param("i", $reportID);
    $stmt->execute();
}

header("Location: admin_reports.php?id=" . $reportID);
exit;
?>