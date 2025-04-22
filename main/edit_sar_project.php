<?php
session_start();
require_once '../account/connect.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $link = $_POST['link'] ?? null;
    $icon_image = null;

    if (!$id || !$title || !$link) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit();
    }

    // Fetch the existing icon filename from DB
    $stmt = $con->prepare("SELECT icon_image FROM sars_projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Project not found']);
        exit();
    }

    $row = $result->fetch_assoc();
    $existingIconPath = $row['icon_image'];
    $stmt->close();

    // Handle new icon upload
    if (!empty($_FILES['icon']['name'])) {
        $targetDir = "../main/images/uploads/icons/";
        $fileExtension = pathinfo($_FILES["icon"]["name"], PATHINFO_EXTENSION);

        // Sanitize title and append timestamp to ensure uniqueness
        $safeTitle = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title));
        $timestamp = time();
        $newIconName = $safeTitle . "_" . $timestamp . "." . $fileExtension;
        $newIconPath = $targetDir . $newIconName;

        if (move_uploaded_file($_FILES["icon"]["tmp_name"], $newIconPath)) {
            // Delete the old icon
            if (!empty($existingIconPath) && file_exists($existingIconPath)) {
                unlink($existingIconPath);
            }
            $icon_image = $newIconPath;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Icon upload failed']);
            exit();
        }
    }

    // Update the project
    if ($icon_image) {
        $stmt = $con->prepare("UPDATE sars_projects SET title = ?, link = ?, icon_image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $link, $icon_image, $id);
    } else {
        $stmt = $con->prepare("UPDATE sars_projects SET title = ?, link = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $link, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Project updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating project']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
