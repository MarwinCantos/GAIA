<?php
session_start();
require_once '../account/connect.php';

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch user data
$sql = "SELECT username, email, image_states FROM account";
$result = $con->query($sql);

// Fetch all SAR project icons into an array
$sar_sql = "SELECT icon_image FROM sars_projects ORDER BY id DESC";
$sar_result = $con->query($sar_sql);

$sar_icons = [];
if ($sar_result->num_rows > 0) {
    while ($sar_row = $sar_result->fetch_assoc()) {
        $sar_icons[] = $sar_row['icon_image']; // Store all icon paths
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
    <link rel="stylesheet" href="../admin/admin.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
</head>
<body>
    <!-- Navigation Bar -->
   <span class="burger-button" id="burgerButton">☰</span>
    <div class="sidebar closed" id="sidebar">
        <div class="logo">
            <img alt="Logo of Project GAIA" height="50" src="images/LOGO2.png" width="100"/>
        </div>
        <div class="menu">
            <ul>
                <li>
                    <a href="../main/GAIA.php">
                        <i class="fas fa-home"></i>
                        <span style="margin-left: 25px; font-family: 'Trocchi', serif;">Home</span>
                    </a>
                </li>
                <li>
                    <a href="../about/about.php">
                        <i class="fas fa-info-circle"></i>
                        <span style="margin-left: 25px; font-family: 'Trocchi', serif;">About</span>
                    </a>
                </li>
                <li>
                   
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
        <h1>User List</h1>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search...">
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>SAR</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";

                        // Get image states for each user
                        $imageStates = explode(',', $row["image_states"] ?? str_repeat("1,", count($sar_icons) - 1) . "1");
                        $imageStates = array_pad($imageStates, count($sar_icons), "1");

                        echo '<td class="icon-row">';
                        foreach ($sar_icons as $index => $icon_path) {
                            $darkClass = ($imageStates[$index] === "0") ? "darken" : "";
                            echo '<img class="toggle-dark ' . $darkClass . '" 
                                        src="' . htmlspecialchars($icon_path) . '" 
                                        alt="SAR Icon ' . ($index + 1) . '" 
                                        data-username="' . htmlspecialchars($row["username"]) . '" 
                                        data-img-id="' . ($index + 1) . '">';
                        }
                        echo '</td>';

                        // Delete account button
                        echo '<td>
                                <form method="POST" action="delete_account.php" onsubmit="return confirm(\'Are you sure you want to delete this account?\');">
                                    <input type="hidden" name="username" value="' . htmlspecialchars($row["username"]) . '">
                                    <button type="submit" class="delete-button">Delete</button>
                                </form>
                              </td>';
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data found</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <script src="../admin/admin.js"></script>
</body>
</html>
