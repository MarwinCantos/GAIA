<?php
session_start();

require_once '../account/connect.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../GAIA/login.php"); // Redirect to login page
    exit();
}   

// Set the target directories for the uploaded images
$targetDirIcon = "../main/images/uploads/icons/";
$targetDirAbout = "../main/images/uploads/about_images/";

// Ensure directories exist
if (!file_exists($targetDirIcon)) {
    mkdir($targetDirIcon, 0777, true);
}
if (!file_exists($targetDirAbout)) {
    mkdir($targetDirAbout, 0777, true);
}

// Function to handle file upload and renaming
// Function to handle file upload and renaming
function uploadFile($file, $targetDir) {
    $fileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    // Allow only JPG and PNG files
    if (!in_array($fileType, ['png', 'jpg', 'jpeg'])) {
        return ["error" => "Sorry, only JPG, JPEG, and PNG files are allowed."];
    }

    // Get the current number of files in the directory
    $existingFiles = scandir($targetDir);
    $imageCount = count($existingFiles) - 2; // Subtract 2 to exclude '.' and '..'

    // Generate a new filename
    $newFilename = ($imageCount + 1) . "." . $fileType; // e.g., 1.jpg, 2.png
    $targetFile = $targetDir . $newFilename;

    // Check if the file already exists
    if (file_exists($targetFile)) {
        return ["error" => "Sorry, file already exists."];
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return ["error" => "Sorry, your file is too large."];
    }

    // Attempt to upload the file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ["success" => true, "path" => $targetFile];
    } else {
        return ["error" => "Sorry, there was an error uploading your file."];
    }
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadedFiles = [];
    $errors = [];

    // Handle Icon Image Upload
    if (isset($_FILES["icon"]) && $_FILES["icon"]["error"] == 0) {
        $result = uploadFile($_FILES["icon"], $targetDirIcon);
        if (isset($result["error"])) {
            $errors[] = $result["error"];
        } else {
            $uploadedFiles['icon'] = $result["path"];
        }
    }

    // Handle About Image Upload
    if (isset($_FILES["about"]) && $_FILES["about"]["error"] == 0) {
        $result = uploadFile($_FILES["about"], $targetDirAbout);
        if (isset($result["error"])) {
            $errors[] = $result["error"];
        } else {
            $uploadedFiles['about'] = $result["path"];
        }
    }

    // Get title, description, and link from the form
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $link = isset($_POST['link']) ? $_POST['link'] : '';

    // Validate the input fields
    if (empty($title) || empty($description) || empty($link)) {
        $errors[] = "Title, description, and link are required.";
    }

    // If any upload fails, delete all successful uploads
    if (!empty($errors)) {
        foreach ($uploadedFiles as $file) {
            unlink($file);
        }
        $message = implode("\n", $errors);
    } else {
        // Assign variables for icon and about image
        $iconPath = isset($uploadedFiles['icon']) ? $uploadedFiles['icon'] : null;
        $aboutPath = isset($uploadedFiles['about']) ? $uploadedFiles['about'] : null;

        // Insert the data into the database
        $sql = "INSERT INTO sars_projects (title, description, link, icon_image, about_image) 
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param(
                "sssss", 
                $title, 
                $description, 
                $link, 
                $iconPath, 
                $aboutPath
            );

            if ($stmt->execute()) {
                $message = "Project added successfully!";
            } else {
                $message = "Error inserting data into the database.";
            }

            $stmt->close();
        } else {
            $message = "Error preparing the SQL statement.";
        }
    }

    // Show alert and redirect
    echo "<script>alert('$message'); window.location.href = '../main/GAIA.php';</script>";
    exit();
}
?>
