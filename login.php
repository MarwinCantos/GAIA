<?php
session_start();
require_once '../GAIA/account/connect.php';

if (isset($_POST['login-button'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Check in the admin table
        $query = $con->prepare("SELECT * FROM admin WHERE Email = ? AND Password = ?");
        $query->bind_param("ss", $email, $password);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['username'] = $user['Username'];
            $_SESSION['email'] = $user['Email'];

            echo "<script>
                    alert('✅ Admin Login Successful!');
                    window.location.href = '../gaia/main/GAIA.php'; // Admin redirect
                  </script>";

            exit();

        } else {
            // Check in the account table
            $query = $con->prepare("SELECT * FROM account WHERE Email = ? AND Password = ?");
            $query->bind_param("ss", $email, $password);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                $_SESSION['username'] = $user['Username'];
                $_SESSION['email'] = $user['Email'];

                echo "<script>
                        alert('✅ User Login Successful!');
                        window.location.href = '../gaia/main_user/GAIA_user.php'; // User redirect
                      </script>";

                exit();

            } else {
                echo "<script>alert('❌ Incorrect email or password!');</script>";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project GAIA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../GAIA/account/login.css">
</head>
<body>
    <div class="overlay">
        <div class="logo-container">
            <img src="../GAIA/account/images/bsu_logo.png" alt="Batangas State University logo">
            <div>
                <p>Batangas</p>
                <p>State University</p>
            </div>
        </div>
        <div class="right-logo">
            <img src="../GAIA/account/images/spartan_logo.png" alt="Red Spartan logo">
        </div>
        <div class="login-container">
            <img src="../GAIA/account/images/gaia_logo.png" alt="GAIA logo" width="200">
            
            <form method="POST" action="">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input id="email" name="email" type="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input id="password" name="password" type="password" placeholder="Password" required>
                    <span class="eye-icon" onclick="togglePassword()">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </span>
                </div>

                
                <button class="login-button" type="submit" name="login-button">Login</button>
            </form>

            <div class="footer-links">
                <a href="../gaia/account/create.php">Create Account</a>
                <a href="../gaia/account/forgot.php">Forgot Password</a>
            </div>
        </div>
    </div>
        <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eyeIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
        </script>




</body>
</html>