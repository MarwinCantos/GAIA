<?php
session_start();
require_once '../account/connect.php';

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$avatar = $data['avatar'] ?? null;

if (!$avatar) {
    http_response_code(400);
    echo "Invalid input";
    exit();
}

$sql = "UPDATE account SET Avatar = ? WHERE Email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $avatar, $_SESSION['email']);
$stmt->execute();

if ($stmt->affected_rows >= 0) {
    echo "Avatar updated";
} else {
    http_response_code(500);
    echo "Failed to update avatar";
}

$stmt->close();
$con->close();
?>
