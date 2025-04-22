document.addEventListener('DOMContentLoaded', function() {
    var burgerButton = document.getElementById('burgerButton');
    var sidebar = document.getElementById('sidebar');

    if (burgerButton && sidebar) {
        burgerButton.addEventListener('click', function(event) {
            this.classList.toggle('active');
            sidebar.classList.toggle('closed');
            
            if (sidebar.classList.contains('closed')) {
                this.innerHTML = '&#9776;';
            } else {
                this.innerHTML = '&times;';
            }
            event.stopPropagation();
        });

        document.addEventListener('click', function(event) {
            if (!sidebar.contains(event.target) && !burgerButton.contains(event.target)) {
                sidebar.classList.add('closed');
                burgerButton.classList.remove('active');
                burgerButton.innerHTML = '&#9776;';
            }
        });
    }

    let navImages = document.querySelectorAll(".nav-item img");

    navImages.forEach(img => {
        img.addEventListener("click", function () {
            this.classList.add("active"); // Only add the class, never remove
        });
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const popups = document.querySelectorAll(".popup");
    const buttons = document.querySelectorAll(".popup-button");
    const overlay = document.getElementById("overlay");

    buttons.forEach((button, index) => {
        button.addEventListener("click", () => {
            document.getElementById(`popup${index}`).style.display = "block";
            overlay.style.display = "block";
        });
    });

    popups.forEach((popup) => {
        popup.querySelector(".close").addEventListener("click", () => {
            popup.style.display = "none";
            overlay.style.display = "none";
        });
    });

    overlay.addEventListener("click", () => {
        popups.forEach(popup => popup.style.display = "none");
        overlay.style.display = "none";
    });
});

// Open Edit Modal
function openEditModal() {
    document.getElementById("editModal").style.display = "flex";
}

// Close Edit Modal
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

// Ensure modal is hidden on page load
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("editModal").style.display = "none";
});


// Open the Edit Modal and Pre-fill Data
function editProject(id) {
    fetch(`../main/get_sar_projects.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById("editId").value = data.id;
            document.getElementById("editTitle").innerText = "Edit Project: " + data.title;
            document.getElementById("editTitleInput").value = data.title; // NEW: populate input field
            document.getElementById("editDescription").value = data.description;
            document.getElementById("currentImage").src = data.about_image;
            openEditModal();
        })
        .catch(error => console.error("Error loading project details:", error));
}


// Submit the Edit Form
document.getElementById("editForm").addEventListener("submit", function(event) {
    event.preventDefault();
    let formData = new FormData(this);

    fetch("../about/edit_about.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            closeEditModal();
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    })
    .catch(error => console.error("Error updating project:", error));
});


function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('currentImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

