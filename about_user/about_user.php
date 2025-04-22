<?php
session_start();
require_once '../account/connect.php';

// Fetch projects from database
$sql = "SELECT id, title, description, about_image, icon_image FROM sars_projects";
$result = $con->query($sql);

$projects = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project GAIA</title>
    <link rel="stylesheet" href="../about/about.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="page-wrapper">

    <!-- Background Container -->
    <div class="background-container"></div>

    <!-- Navigation Bar -->
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
                    <a href="#">
                        <i class="fas fa-info-circle"></i>
                        <span style="margin-left: 25px; font-family: 'Trocchi', serif;">About</span>
                    </a>
                </li>
                <li>
                    <a href="../account_2/account.php">
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


    <!-- Floating GAIA Box -->
    <div class="gaia-box">
        <h1><img src="images/logo_light.png" alt="GAIA Logo" class="gaia-logo"></h1>
        <p>The GAIA project harnesses the power of Synthetic Aperture Radar (SAR), Geographic Information Systems (GIS), and Machine Learning to develop innovative solutions for environmental monitoring, disaster risk reduction, and sustainable development.</p>
    </div>

    <!-- Background Overlay -->
    <div id="overlay"></div>

    <!-- Dynamic Buttons for Pop-ups -->
    <div class="button-container">
        <?php foreach ($projects as $index => $project): ?>
            <div class="popup-wrapper">
                <img src="<?= htmlspecialchars($project['about_image']) ?>" class="popup-button" id="popupBtn<?= $index ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                <span class="popup-label"><?= htmlspecialchars($project['title']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Dynamic Popup Windows -->
    <?php foreach ($projects as $index => $project): ?>
        <div id="popup<?= $index ?>" class="popup">
            <div class="popup-content">
                <span class="close">&times;</span>
                <img src="<?= htmlspecialchars($project['icon_image']) ?>">
                <h2><?= htmlspecialchars($project['title']) ?></h2>
                <p><?= htmlspecialchars($project['description']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
    <footer class="site-footer">
        <div class="footer-content">
            <h2>Important Links</h2>
            <div class="footer-links">
                <a href="https://batstateu.edu.ph/" target="_blank" title="BatStateU">
                    <img src="../about/images/BatStateU.png" alt="BatStateU" />
                </a>
                <a href="https://www.linkedin.com/school/batstateutheneu/posts/?feedView=all" target="_blank" title="LinkedIn">
                    <img src="../about/images/linkedin.png" alt="LinkedIn" />
                </a>
                <a href="https://www.google.com/maps?sca_esv=d52286dabd1f67ed&output=search&q=batangas+state+university" target="_blank" title="Google Maps">
                    <img src="../about/images/google.png" alt="Google Maps" />
                </a>
                <a href="https://www.youtube.com/watch?v=07ZQoJYDtn0&ab_channel=ProjectGAIA" target="_blank" title="YouTube">
                    <img src="../about/images/youtube.png" alt="YouTube" />
                </a>
            </div>
            <p class="footer-note">&copy; <?= date('Y') ?> Project GAIA. All rights reserved.</p>
        </div>
    </footer>


    <!-- Link the external JavaScript file -->
    <script src="../about/about.js"></script>
</body>
</html>
