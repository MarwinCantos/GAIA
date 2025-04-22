<?php
session_start();
require_once '../account/connect.php';
require '../vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['email'])) {
    echo "Email not provided!";
    exit;
}

$email = trim($_POST['email']);
$_SESSION['email'] = $email;

// Generate a random 6-digit OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

// Save OTP to database
$sql = "UPDATE account SET OTP = ? WHERE Email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $otp, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Send OTP using PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.projectgaia@gmail.com'; 
        $mail->Password = 'wzip raeh vfov chqz'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;


        // Email content
        $mail->setFrom('noreply.projectgaia@gmail.com', 'GAIA');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body = "Hello,\n\nYour One-Time Password (OTP) for resetting your password is: $otp\n\nPlease enter this code to proceed.";

        $mail->send();
        echo "OTP sent to your email. Please check your inbox.";
    } catch (Exception $e) {
        echo "Failed to send OTP. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Failed to generate OTP. Email not found.";
}

$stmt->close();
$con->close();
