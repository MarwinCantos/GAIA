<?php
session_start();
require_once '../account/connect.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $con->prepare("SELECT * FROM sars_projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();
    echo json_encode($project);
} else {
    $result = $con->query("SELECT * FROM sars_projects ORDER BY id DESC");
    $projects = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($projects);
}
?>
