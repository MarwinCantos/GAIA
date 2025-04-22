document.addEventListener("DOMContentLoaded", function () {
    // Password Change Modal Handling
    const changePasswordBtn = document.getElementById("changePassword");
    const modal = document.getElementById("passwordModal");
    const closeBtn = document.querySelector(".close");
    const cancelButton = document.getElementById("cancelButton");

    if (changePasswordBtn) {
        changePasswordBtn.addEventListener("click", () => {
            modal.style.display = "flex";
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    const changePasswordForm = document.getElementById("changePasswordForm");

    changePasswordForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        const oldPasswordInput = document.getElementById("oldPassword");
        const newPasswordInput = document.getElementById("newPassword");
        const confirmPasswordInput = document.getElementById("confirmPassword");

        const oldPassword = oldPasswordInput.value;
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (newPassword !== confirmPassword) {
            alert("New password and confirm password do not match.");
            changePasswordForm.reset(); // Clear all input fields
            return;
        }

        // Send data to PHP via Fetch API
        fetch("change_password.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                oldPassword: oldPassword,
                newPassword: newPassword,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Password updated successfully!");
                changePasswordForm.reset(); // Clear all input fields
                modal.style.display = "none";
                
                // Redirect to logout.php after password change
                window.location.href = "../account/logout.php";
            } else {
                alert(data.message);

                // Clear only the password fields if the update fails
                changePasswordForm.reset(); // Clear all input fields
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            alert("An error occurred. Please try again.");
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const avatarModal = document.getElementById('avatarModal');
    const openBtn = document.getElementById('openAvatarModal');
    const closeBtn = document.getElementById('closeAvatarModal');
    const profilePic = document.getElementById('profile-pic');

    openBtn.onclick = () => avatarModal.style.display = 'block';
    closeBtn.onclick = () => avatarModal.style.display = 'none';
    window.onclick = (e) => { if (e.target == avatarModal) avatarModal.style.display = 'none'; };

    document.querySelectorAll('.avatar-option').forEach(img => {
        img.addEventListener('click', () => {
            const selectedAvatar = img.getAttribute('src');
            profilePic.src = selectedAvatar;
            avatarModal.style.display = 'none';

            fetch('save_avatar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ avatar: selectedAvatar })
            })
            .then(response => response.text())
            .then(data => console.log(data))
            .catch(error => console.error("Avatar update error:", error));
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    var burgerButton = document.getElementById('burgerButton');
    var sidebar = document.getElementById('sidebar');

    if (burgerButton && sidebar) {
        burgerButton.addEventListener('click', function(event) {
            this.classList.toggle('active');
            sidebar.classList.toggle('closed');
            
            // Ensure button stays on top
            if (sidebar.classList.contains('closed')) {
                this.innerHTML = '&#9776;'; // Hamburger icon
            } else {
                this.innerHTML = '&times;'; // Close icon
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
});

document.addEventListener("DOMContentLoaded", function () {
    // Password toggle functionality
    document.querySelectorAll(".toggle-password").forEach((icon) => {
        icon.addEventListener("click", function () {
            const input = document.getElementById(this.getAttribute("data-target"));
            if (input.type === "password") {
                input.type = "text";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            }
        });
    });
});
