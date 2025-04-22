<?php
session_start();
require_once '../account/connect.php';

if (isset($_POST['verify'])) {
    $entered_otp = $_POST['otp'];

    if ($entered_otp === $_SESSION['otp']) {
        $username = $_SESSION['temp_username'];
        $email = $_SESSION['temp_email'];
        $password = $_SESSION['temp_password']; // Plain text password

        $stmt = $con->prepare("INSERT INTO account (Username, Email, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            unset($_SESSION['otp'], $_SESSION['temp_email'], $_SESSION['temp_username'], $_SESSION['temp_password']);
            echo "<script>
                    alert('✅ Registration Successful!');
                    window.location.href = '../login.php';
                  </script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Incorrect OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../account/create.css" />

</head>
<body>
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
    
    <div class="overlay">
        <div class="login-container">
            <h2>Email Verification</h2>
            <p>Please enter the OTP sent to your email.</p>
            <form method="post">
                <div class="input-group">
                    <input type="text" name="otp" placeholder="Enter OTP" required />
                </div>
                <button type="submit" name="verify">Verify</button>
            </form>
            <div class="footer-links">
                <a href="../login.php">Back to Login</a>
            </div>
        </div>
    </div>
</body>

</html>
