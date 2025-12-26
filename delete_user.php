<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Prevent self-deletion
    if ($_SESSION['user_id'] == $id) {
        echo "<script>alert('You cannot delete your own account.'); window.location.href='getallusers.php';</script>";
        exit;
    }

    // Delete projects created by this user (first, to avoid FK constraint error)
    $delProjects = $conn->prepare("DELETE FROM project_db WHERE created_by = ?");
    $delProjects->bind_param("i", $id);
    $delProjects->execute();
    $delProjects->close();

    // Now delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.'); window.location.href='getallusers.php';</script>";
    } else {
        echo "<script>alert('Failed to delete user.'); window.location.href='getallusers.php';</script>";
    }

    $stmt->close();
} else {
    header("Location: getallusers.php");
    exit;
}
?>
