<?php
session_start();
include 'connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Only proceed if a valid POST request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sl_no'])) {
    $project_id = intval($_POST['sl_no']); // Ensure it's an integer

    // Optional: You may also check if the user is authorized to delete this record
    $role = $_SESSION['role'];
    $user_id = $_SESSION['user_id'];

    if ($role === 'admin') {
        // Admin can delete any project
        $stmt = $conn->prepare("DELETE FROM project_db WHERE id = ?");
        $stmt->bind_param("i", $project_id);
    } else {
        // Users can only delete their own projects
        $stmt = $conn->prepare("DELETE FROM project_db WHERE id = ? AND created_by = ?");
        $stmt->bind_param("ii", $project_id, $user_id);
    }

    if ($stmt->execute()) {
        // Redirect with success
        header("Location: project_review.php?status=deleted");
        exit;
    } else {
        // Handle deletion error
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect if accessed incorrectly
    header("Location: project_review.php");
    exit;
}
?>
