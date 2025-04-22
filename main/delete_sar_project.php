<?php
session_start();
require_once '../account/connect.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;

    if ($id) {
        // First, retrieve the image file paths from the database
        $stmt = $con->prepare("SELECT icon_image, about_image FROM sars_projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($iconImage, $aboutImage);
        $stmt->fetch();
        $stmt->close();

        // Delete the project from the database
        $stmt = $con->prepare("DELETE FROM sars_projects WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete the images if they exist
            if ($iconImage && file_exists($iconImage)) {
                unlink($iconImage);
            }
            if ($aboutImage && file_exists($aboutImage)) {
                unlink($aboutImage);
            }

            echo json_encode(['status' => 'success', 'message' => 'Project and images deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting project']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
