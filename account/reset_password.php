<?php
session_start();
require_once '../account/connect.php';

// Ensure database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if email exists in session
if (!isset($_SESSION['email'])) {
    die("Error: Email not found in session.");
}

// Validate input
$newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : '';
$confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';

if (empty($newPassword) || empty($confirmPassword)) {
    die("Error: All fields are required.");
}

if ($newPassword !== $confirmPassword) {
    die("Error: Passwords do not match.");
}

if (strlen($newPassword) < 8) {
    die("Error: Password must be at least 8 characters.");
}

// Update password directly without hashing
$sql = "UPDATE account SET Password = ? WHERE Email = ?";
$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $newPassword, $_SESSION['email']);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: Failed to update password.";
    }
    $stmt->close();
} else {
    echo "Error: Failed to prepare the statement.";
}

// Close connection
$con->close();
?>
