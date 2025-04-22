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
    $description = $_POST['description'] ?? null;
    $about_image = null;

    if (!$id || !$title || !$description) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit();
    }

    // Fetch the existing project image from the database
    $stmt = $con->prepare("SELECT about_image FROM sars_projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Project not found']);
        exit();
    }

    $row = $result->fetch_assoc();
    $existingImagePath = $row['about_image']; 
    $stmt->close();

    // Handle file upload if a new image is provided
    if (!empty($_FILES['about_images']['name'])) {
        $targetDir = "../main/images/uploads/about_images/";
        
        // Extract file extension (e.g., .png, .jpg)
        $fileExtension = pathinfo($_FILES["about_images"]["name"], PATHINFO_EXTENSION);
        
        // Rename new image using the project title
        $newImageName = strtolower(str_replace(" ", "_", $title)) . "." . $fileExtension;
        $newImagePath = $targetDir . $newImageName;

        // Move uploaded file
        if (move_uploaded_file($_FILES["about_images"]["tmp_name"], $newImagePath)) {
            // Delete the old image if it exists
            if (!empty($existingImagePath) && file_exists($existingImagePath)) {
                unlink($existingImagePath);
            }
            $about_image = $newImagePath; // Assign new file path to update DB
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Image upload failed']);
            exit();
        }
    }

    // Prepare update query
    if ($about_image) {
        $stmt = $con->prepare("UPDATE sars_projects SET title = ?, description = ?, about_image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $about_image, $id);
    } else {
        $stmt = $con->prepare("UPDATE sars_projects SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
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
