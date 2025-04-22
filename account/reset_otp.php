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

// Reset OTP to 0
$sql = "UPDATE account SET OTP = 0 WHERE Email = ?";
$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $_SESSION['email']);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: Failed to reset OTP.";
    }
    $stmt->close();
} else {
    echo "Error: Failed to prepare the statement.";
}

// Close connection
$con->close();
?>
