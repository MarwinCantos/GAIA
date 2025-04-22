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
    <link rel="stylesheet" href="../main/GAIA.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
</head>
<body>

   <!-- Navigation Bar -->
   <span class="burger-button" id="burgerButton">☰</span>
   <button class="close-all-button" onclick="closeAllWindows()">Close All Windows</button>
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
                    <a href="../admin/admin.php">
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
        <button class="plus-button" onclick="openModal()">Add SAR Projects</button>
        <button class="manage-button" onclick="openManageModal()">
            <i class="fas fa-cogs"></i> Manage SAR Projects
        </button>


    </div>


    <form action="../main/upload.php" method="POST" enctype="multipart/form-data">
    <!-- Modal -->
        <div id="modalForm" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Add SARS Project</h2>

                <div class="input-container">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" placeholder="Enter title" required>
                </div>

                <div class="input-container">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Enter description" required></textarea>
                </div>

                <div class="input-container">
                    <label for="link">WebApp Link:</label>
                    <input type="text" id="link" name="link" placeholder="Enter link" required>
                </div>

                <div class="grid">
                    <!-- Icon Upload -->
                    <div class="flex">
                        <label>Upload Icon</label>
                        <div class="upload-box">
                            <button class="clear-image" onclick="clearImage('iconImage')">×</button>
                            <label class="upload-button">
                                <i class="fa fa-image"></i>
                                <img id="iconImage" src="">
                                <input type="file" id="icon-upload" name="icon" accept="image/*" onchange="previewImage(event, 'iconImage')" hidden>
                            </label>
                        </div>
                    </div>

                    <!-- About Image Upload -->
                    <div class="flex">
                        <label>Upload About Image</label>
                        <div class="about-box">
                            <button class="clear-image" onclick="clearImage('aboutImage')">×</button>
                            <label class="about-button">
                                <i class="fa fa-image"></i>
                                <img id="aboutImage" src="">
                                <input type="file" id="about-upload" name="about" accept="image/*" onchange="previewImage(event, 'aboutImage')" hidden>
                            </label>
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="button" class="clear-button" onclick="clearAllInputs()">Clear All</button>
                        <button type="submit" class="save-button">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Manage SAR Projects Modal -->
    <div id="manageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeManageModal()">&times;</span>
            <h2>Manage SAR Projects</h2>
            
            <table class="manage-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Icon</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sarProjectList">
                    <!-- Dynamic Content will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit SAR Project Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit SAR Project</h2>
            
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">

                <label for="editTitle">Title:</label>
                <input type="text" id="editTitle" name="title" required>

                <label for="editLink">WebApp Link:</label>
                <input type="text" id="editLink" name="link" required>

                <label>Current Icon:</label>
                <img id="currentIcon" src="" alt="Icon Preview" width="50">
                <input type="file" id="editIcon" name="icon" accept="image/*" onchange="previewIcon(event)">

                <div class="button-container">
                    <button type="button" class="clear-button" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="save-button">Save Changes</button>
                </div>
            </form>
        </div>
    </div>






    <!-- Empty Frames -->
    <div class="SAR-frame" id="SARframe"></div>




    <script src="../main/GAIA.js"></script>

</body>
</html>