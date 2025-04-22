<?php
session_start();
require_once '../account/connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../GAIA/login.php"); // Redirect to login page
    exit();
}

$sql = "SELECT title, link, icon_image FROM sars_projects ORDER BY id DESC";
$result = $con->query($sql);

?>


<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project GAIA</title>
    <link rel="stylesheet" href="../main_user/GAIA_user.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <script>
        window.onload = function () {
            fetch("user_get_image_states.php") // Fetch user-specific image states
                .then(response => response.json())
                .then(data => {
                    let statesArray = data.split(",").map(Number); // Convert string to array

                    // Select all nav-item elements
                    let navItems = document.querySelectorAll(".navbar-sar .nav-item");

                    navItems.forEach((item, index) => {
                        if (statesArray[index] === 0) {
                            item.style.display = "none"; // Hide if state is 0
                        } else {
                            item.style.display = "block"; // Show if state is 1
                        }
                    });
                })
                .catch(error => console.error("Error fetching image states:", error));
        };
    </script>
</head>
<body>

    <span class="burger-button" id="burgerButton">☰</span>
    <button class="close-all-button" onclick="closeAllWindows()">Close All Windows</button>
    <div class="sidebar closed" id="sidebar">
        <div class="logo">
            <img alt="Logo of Project GAIA" height="50" src="images/LOGO2.png" width="100"/>
        </div>
        <div class="menu">
            <ul>
                <li>
                    <a href="#">
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

    <div class="navbar-sar">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="nav-item">
                <a href="#" onmouseover="showPreview(this, '<?= htmlspecialchars($row['link']) ?>', '<?= htmlspecialchars($row['title']) ?>')" 
                onclick="addFrame('<?= htmlspecialchars($row['link']) ?>', '<?= htmlspecialchars($row['title']) ?>')">
                    <img src="<?= htmlspecialchars($row['icon_image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>"> 
                    <span class="custom-tooltip"><?= htmlspecialchars($row['title']) ?></span>
                </a>
            </div>
        <?php endwhile; ?>

    </div>

    <!-- Empty Frames -->
    <div class="SAR-frame" id="SARframe"></div>




    <script src="../main_user/GAIA_user.js"></script>

</body>
</html>