<?php
session_start(); // Start session to track logged-in user
require_once '../account/connect.php';

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Assuming you store the logged-in user's username in a session variable
$logged_in_user = $_SESSION['username']; 

$sql = "SELECT image_states FROM account WHERE Username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $logged_in_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row["image_states"]); // Return image_states as JSON
} else {
    echo json_encode("0,0,0,0,0,0,0,0,0"); // Default if no record found
}

$stmt->close();
$con->close();
?>
