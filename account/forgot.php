<?php
session_start();
require_once '../account/connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="/GAIA/favicon.png" type="image/png">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <div class="overlay">
        <div class="logo-container">
            <img src="images/bsu_logo.png" alt="Batangas State University logo" />
            <div>
                <p>Batangas</p>
                <p>State University</p>
            </div>
        </div>
        
        <div class="right-logo">
            <img src="images/spartan_logo.png" alt="Red Spartan logo" />
        </div>  
        
        <div class="login-container">
            <img src="images/gaia_logo.png" alt="GAIA logo" width="200" />
            <p style="font-size: 18px; font-weight: bold;">Forgot Your Password?</p>
            <p class="text-reset">Enter your email to reset your password.</p>

            <!-- Email Input for OTP Generation -->
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" placeholder="Email" required />
            </div>
            <button class="login-button" id="generateOtp" onclick="sendOtp()">Send OTP</button>

            <!-- OTP Input Form (Hidden Initially) -->
            <div id="otpForm" style="display: none; margin-top: 20px;">
                <p>Enter the OTP sent to your email.</p>
                <input type="text" id="otpInput" placeholder="Enter OTP" maxlength="6" inputmode="numeric" pattern="[0-9]*" required />
                <button id="verifyOtp" onclick="verifyOtp()">Verify OTP</button>
                <p id="otpMessage" role="alert" class="error-message"></p>
            </div>

            <!-- Password Reset Modal (Hidden by Default) -->
            <div id="passwordModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <h3>Reset Your Password</h3>

                    <label for="newPassword">New Password:</label>
                    <div class="password-container">
                        <input type="password" id="newPassword" placeholder="Enter New Password">
                        <span class="toggle-password" onclick="togglePassword('newPassword', 'eye-icon1')">
                            <i class="fas fa-eye" id="eye-icon1"></i>
                        </span>
                    </div>

                    <label for="confirmPassword">Confirm Password:</label>
                    <div class="password-container">
                        <input type="password" id="confirmPassword" placeholder="Confirm New Password">
                        <span class="toggle-password" onclick="togglePassword('confirmPassword', 'eye-icon2')">
                            <i class="fas fa-eye" id="eye-icon2"></i>
                        </span>
                    </div>

                    <p id="passwordMessage" style="color: red;"></p>

                    <div class="button-group">
                        <button class="submit-btn" onclick="resetPassword()">Submit</button>
                        <button class="close-btn" onclick="closeModal()">Close</button>
                    </div>
                </div>
            </div>



            <!-- Back to Login Link -->
            <div class="footer-links">
                <a href="../login.php">Back to Login</a>
            </div>
        </div>
    </div>

    <script src="forgot_password.js"></script>
</body>
</html>
