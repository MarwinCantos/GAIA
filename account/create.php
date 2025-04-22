<?php
session_start();
require_once '../account/connect.php';
require '../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send OTP using PHPMailer
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.projectgaia@gmail.com'; 
        $mail->Password = 'wzip raeh vfov chqz'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Content
        $mail->setFrom('noreply.projectgaia@gmail.com', 'GAIA');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP for Account Verification';
        $mail->Body = "Your OTP is: $otp. Please enter this code to verify your email.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // No hashing

    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
    } else {
        // Check if email already exists
        $verify_query = $con->prepare("SELECT Email FROM account WHERE Email=?");
        $verify_query->bind_param("s", $email);
        $verify_query->execute();
        $verify_query->store_result();

        if ($verify_query->num_rows > 0) {
            echo "<script>alert('This email is already used. Try another one!');</script>";
        } else {
            // Generate a 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $_SESSION['otp'] = $otp;
            $_SESSION['temp_email'] = $email;
            $_SESSION['temp_username'] = $username;
            $_SESSION['temp_password'] = $password;

            if (sendOTP($email, $otp)) {
                echo "<script>
                        alert('OTP has been sent to your email. Please verify.');
                        window.location.href = 'create_verify_otp.php';
                      </script>";
            } else {
                echo "<script>alert('Error sending OTP. Please try again later.');</script>";
            }
        }
        $verify_query->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project GAIA</title>
    <link rel="icon" href="/GAIA/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../account/login.css" />
</head>
<body>
    <div class="overlay">
        <div class="logo-container">
            <img src="../account/images/bsu_logo.png" alt="Batangas State University logo" />
            <div>
                <p>Batangas</p>
                <p>State University</p>
            </div>
        </div>
        <div class="right-logo">
            <img src="../account/images/spartan_logo.png" alt="Red Spartan logo" />
        </div>
        <div class="login-container">
            <img src="../account/images/gaia_logo.png" alt="GAIA logo" width="200" />
            <p style="font-size: 30px; font-weight: bold;">First Time User?</p>
            <form method="post" action="">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input id="username" name="username" type="text" placeholder="Username" required />
                </div>
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
                <button class="login-button" type="submit" name="submit">Create Account</button>
            </form>

            <div class="footer-links">
                <a href="../login.php">Back to Login</a>
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
