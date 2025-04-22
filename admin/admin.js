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


document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-dark").forEach(img => {
        img.addEventListener("click", function () {
            let username = this.dataset.username;
            let imgId = this.dataset.imgId;
            let action = this.classList.contains("darken") ? "add" : "remove"; // Toggle state

            // Send AJAX request to update database
            fetch("update_image_state.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `username=${username}&img_id=${imgId}&action=${action}`
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    this.classList.toggle("darken"); // Toggle darken effect
                } else {
                    alert("Error updating database: " + data);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});





document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        let nameCell = row.cells[0];
        let emailCell = row.cells[1];
        let name = nameCell?.textContent.toLowerCase();
        let email = emailCell?.textContent.toLowerCase();
        
        // Remove existing highlights
        [nameCell, emailCell].forEach(cell => {
            cell.innerHTML = cell.textContent;
        });

        // Show or hide rows based on match
        if (name.includes(filter) || email.includes(filter)) {
            row.style.display = "";

            // Apply highlight effect
            if (filter) {
                highlightText(nameCell, filter);
                highlightText(emailCell, filter);
            }
        } else {
            row.style.display = "none";
        }
    });
});

function highlightText(cell, filter) {
    const text = cell.textContent;
    const regex = new RegExp(`(${filter})`, 'gi');
    cell.innerHTML = text.replace(regex, match => `<span class="highlight">${match}</span>`);
}
