<?php
session_start();
require_once '../account/connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../GAIA/login.php");
    exit();
}

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch user data, including Avatar
$sql = "SELECT Username, Email, Avatar FROM account WHERE Email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project GAIA</title>
    <link rel="icon" href="/GAIA/favicon.png" type="image/png">
    <link rel="stylesheet" href="../account_2/account.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <span class="burger-button" id="burgerButton">☰</span>
    <div class="sidebar closed" id="sidebar">
        <div class="logo">
            <img alt="Logo of Project GAIA" height="50" src="images/LOGO2.png" width="100"/>
        </div>
        <div class="menu">
            <ul>
                <li>
                    <a href="../main_user/GAIA_user.php">
                        <i class="fas fa-home"></i>
                        <span style="margin-left: 25px; font-family: 'Trocchi', serif;">Home</span>
                    </a>
                </li>
                <li>
                    <a href="../about_user/about_user.php">
                        <i class="fas fa-info-circle"></i>
                        <span style="margin-left: 25px; font-family: 'Trocchi', serif;">About</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-users" style="margin-left: -3px;"></i>
                        <span style="margin-left: 25px; font-family: 'Trocchi', serif;">Account</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="logout">
            <a href="../account/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span style="margin-left: 44.5px; font-family: 'Trocchi', serif;">Logout</span>
            </a>
        </div>
    </div>

    <div class="container">
        <div class="content">
            <div class="main">
            <div class="profile-container">
                <?php
                $avatarPath = isset($user['Avatar']) && !empty($user['Avatar']) ? $user['Avatar'] : 'avatars/avatar1.png';
                ?>
                <div class="profile-pic-wrapper">
                    <img id="profile-pic" class="profile-pic" src="<?php echo htmlspecialchars($avatarPath); ?>" alt="User Avatar">
                    <button class="change-pic-btn" id="openAvatarModal">Change</button>
                </div>
            </div>

            <div class="username"><?php echo htmlspecialchars($user['Username'] ?? 'N/A'); ?></div>
            <p class="email"><?php echo htmlspecialchars($user['Email'] ?? 'N/A'); ?></p>


            <div class="Buttons">
                <button id="changePassword">CHANGE PASSWORD</button>
                <button id="signOut" onclick="window.location.href='../account/logout.php'">SIGN OUT</button>
            </div>
        </div>
    </div>

    <!-- Password Modal (existing) -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Change Password</h2>
            <form id="changePasswordForm">
                <label for="oldPassword">Old Password:</label>
                <div class="password-container">
                    <input type="password" id="oldPassword" required>
                    <i class="toggle-password fas fa-eye" data-target="oldPassword"></i>
                </div>

                <label for="newPassword">New Password:</label>
                <div class="password-container">
                    <input type="password" id="newPassword" required>
                    <i class="toggle-password fas fa-eye" data-target="newPassword"></i>
                </div>

                <label for="confirmPassword">Confirm Password:</label>
                <div class="password-container">
                    <input type="password" id="confirmPassword" required>
                    <i class="toggle-password fas fa-eye" data-target="confirmPassword"></i>
                </div>

                <div class="modal-buttons">
                    <button type="button" id="cancelButton" class="cancel-btn">Cancel</button>
                    <button type="submit" id="saveButton" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Avatar Modal -->
    <div id="avatarModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeAvatarModal">&times;</span>
            <h2>Select an Avatar</h2>
            <div class="avatar-grid">
                <img src="avatars/avatar1.png" class="avatar-option">
                <img src="avatars/avatar2.png" class="avatar-option">
                <img src="avatars/avatar3.png" class="avatar-option">
                <img src="avatars/avatar4.png" class="avatar-option">
                <img src="avatars/avatar5.png" class="avatar-option">
                <img src="avatars/avatar6.png" class="avatar-option">
                <img src="avatars/avatar7.png" class="avatar-option">
                <img src="avatars/avatar8.png" class="avatar-option">
                <img src="avatars/avatar9.png" class="avatar-option">
                <img src="avatars/avatar10.png" class="avatar-option">
                <img src="avatars/avatar11.png" class="avatar-option">
                <img src="avatars/avatar12.png" class="avatar-option">
            </div>
        </div>
    </div>

    <script defer src="../account_2/account.js"></script>

</body>
</html>
