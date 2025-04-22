<?php
session_start();
require_once '../account/connect.php';

// Ensure the database connection is available
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if email exists in session
if (!isset($_SESSION['email'])) {
    die("Error: Email not found in session.");
}

// Retrieve the OTP from the POST request
$inputOtp = isset($_POST['otp']) ? trim($_POST['otp']) : '';

// Validate input
if (empty($inputOtp) || !preg_match('/^\d{6}$/', $inputOtp)) {
    die("Invalid OTP format. Please enter a 6-digit number.");
}

// Prepare and execute query safely
$sql = "SELECT OTP FROM account WHERE Email = ?";
$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify OTP
        if ($user['OTP'] === $inputOtp) {
            echo "success"; // Return success for frontend processing
        } else {
            echo "Invalid OTP. Please try again.";
        }
    } else {
        echo "User not found or multiple users detected.";
    }
    
    $stmt->close();
} else {
    echo "Error: Failed to prepare the statement.";
}

// Close connection
$con->close();
?>
