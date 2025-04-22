<?php
session_start();
require_once '../account/connect.php';

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

$logged_in_user = $_SESSION['username'];
$sql = "SELECT image_states FROM account WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $logged_in_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imageStates = explode(',', $row['image_states']);
    echo json_encode($imageStates); // Return states as JSON array
} else {
    echo json_encode(array_fill(0, 9, "1")); // Return default
}

$stmt->close();
$con->close();
?>
