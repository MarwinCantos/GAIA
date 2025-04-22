<?php
session_start();
header("Content-Type: application/json"); // Ensure JSON response

require_once '../account/connect.php';

// Check connection
if ($con->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Initialize response
$response = ["success" => false, "message" => ""];

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["email"])) {
        echo json_encode(["success" => false, "message" => "User not authenticated."]);
        exit;
    }

    $oldPassword = $_POST["oldPassword"] ?? '';
    $newPassword = $_POST["newPassword"] ?? '';
    $email = $_SESSION["email"]; // Use session email

    // Validate input
    if (empty($oldPassword) || empty($newPassword)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Fetch current password (without hashing)
    $stmt = $con->prepare("SELECT Password FROM account WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedPassword);
        $stmt->fetch();

        // Directly compare passwords (⚠️ Less secure)
        if ($oldPassword === $storedPassword) {
            // Update password (without hashing)
            $updateStmt = $con->prepare("UPDATE account SET Password = ? WHERE Email = ?");
            $updateStmt->bind_param("ss", $newPassword, $email);

            if ($updateStmt->execute()) {
                $response["success"] = true;
                $response["message"] = "Password updated successfully!";
            } else {
                $response["message"] = "Failed to update password.";
            }

            $updateStmt->close();
        } else {
            $response["message"] = "Old password is incorrect.";
        }
    } else {
        $response["message"] = "User not found.";
    }

    $stmt->close();
}

// Close connection
$con->close();
echo json_encode($response);
?>
