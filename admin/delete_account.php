<?php
session_start();
require_once '../account/connect.php';

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])) {
    $username = $con->real_escape_string($_POST["username"]);

    $sql = "DELETE FROM account WHERE username = '$username'";

    if ($con->query($sql) === TRUE) {
        echo "<script>
                alert('Account deleted successfully!');
                window.location.href='../admin/admin.php'; // Redirect back to the main page
              </script>";
    } else { 
        echo "<script>
                alert('Error deleting account: " . $con->error . "');
                window.history.back();
              </script>";
    }
}

$con->close();
?>
