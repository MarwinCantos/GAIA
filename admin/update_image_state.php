<?php
session_start();
require_once '../account/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? null;
    $img_id = isset($_POST["img_id"]) ? (int)$_POST["img_id"] : null;
    $action = $_POST["action"] ?? null;

    // Check if required parameters are received
    if (!$username || !$img_id || !$action) {
        die("error: missing parameters");
    }

    // Fetch current state of images
    $sql = "SELECT image_states FROM account WHERE username = ?";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        die("error: failed to prepare statement - " . $con->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("error: query execution failed - " . $stmt->error);
    }

    if ($row = $result->fetch_assoc()) {
        $imageStates = isset($row["image_states"]) && !empty($row["image_states"])
            ? explode(',', $row["image_states"])
            : array_fill(0, 9, "1"); // Default all active if empty

        $imageStates = array_pad($imageStates, 9, "1"); // Ensure exactly 9 elements

        // Validate image ID within bounds
        if ($img_id < 1 || $img_id > 9) {
            die("error: invalid image ID");
        }

        // Toggle state: "0" means deactivated, "1" means active
        $imageStates[$img_id - 1] = ($action === "remove") ? "0" : "1";

        // Convert array back to string
        $newStates = implode(',', $imageStates);

        // Update the database
        $updateSql = "UPDATE account SET image_states = ? WHERE username = ?";
        $updateStmt = $con->prepare($updateSql);

        if (!$updateStmt) {
            die("error: failed to prepare update statement - " . $con->error);
        }

        $updateStmt->bind_param("ss", $newStates, $username);
        if ($updateStmt->execute()) {
            echo "success";
        } else {
            die("error: failed to execute update query - " . $updateStmt->error);
        }
    } else {
        die("error: no user found");
    }

    $stmt->close();
    $con->close();
}
?>
