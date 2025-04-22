// Function to remove the green border when closing frames
function closeFrame(button) {
    let wrapper = button.closest(".frame-wrapper");
    if (wrapper) {
        let iframe = wrapper.querySelector("iframe");
        if (iframe) {
            let url = iframe.src;

            // Find the corresponding image and remove the active class
            document.querySelectorAll(".nav-item a").forEach(anchor => {
                if (anchor.getAttribute("onclick").includes(url)) {
                    let img = anchor.querySelector("img");
                    if (img) img.classList.remove("active");
                }
            });
        }

        wrapper.remove(); // Remove the frame
        adjustFrames(); // Adjust layout
    }
}


function addFrame(url, title) {
    let container = document.getElementById("SARframe");
    let wrappers = container.getElementsByClassName("frame-wrapper");

    for (let frameWrapper of wrappers) {
        let iframe = frameWrapper.querySelector("iframe");
        if (iframe && iframe.src === url) {
            frameWrapper.style.zIndex = "100";
            frameWrapper.classList.add("highlight");
            setTimeout(() => frameWrapper.classList.remove("highlight"), 800);
            return;
        }
    }

    let frameWrapper = document.createElement("div");
    frameWrapper.className = "frame-wrapper";
    frameWrapper.innerHTML = `
        <div class="frame-title">${title}</div>
        <button class="max-btn" onclick="open_new(this, '${url}')">[]</button>
        <button class="close-btn" onclick="closeFrame(this)">X</button>
        <iframe src="${url}"></iframe>
    `;

    container.appendChild(frameWrapper);
    adjustFrames();

    setTimeout(() => {
        frameWrapper.classList.add("show");
    }, 10);
}

function adjustFrames() {
    let container = document.getElementById("SARframe");
    let wrappers = container.getElementsByClassName("frame-wrapper");
    let count = wrappers.length;

    if (count === 0) return;

    container.style.display = "grid";

    if (count === 1) {
        container.style.gridTemplateColumns = "1fr";
        container.style.gridTemplateRows = "1fr";
    } else if (count === 2) {
        container.style.gridTemplateColumns = "repeat(2, 1fr)";
        container.style.gridTemplateRows = "1fr";
    } else if (count === 3) {
        container.style.gridTemplateColumns = "repeat(3, 1fr)";
        container.style.gridTemplateRows = "1fr";
    } else {
        // 4 or more frames: Ensure at least 2 rows and 2 columns
        let cols = Math.ceil(Math.sqrt(count)); // Calculate a balanced column count
        let rows = Math.ceil(count / cols); // Ensure enough rows to fit all frames

        container.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
        container.style.gridTemplateRows = `repeat(${rows}, 1fr)`;
    }
}




// Function to close all windows and remove green borders
function closeAllWindows() {
    let container = document.getElementById("SARframe");
    container.innerHTML = ""; // Remove all frames

    // Close minimized windows
    openWindows.forEach(winObj => {
        if (winObj.ref && !winObj.ref.closed) {
            winObj.ref.close();
        }
    });
    openWindows = []; // Clear open windows list

    // Remove active class from all images
    document.querySelectorAll(".nav-item img").forEach(img => img.classList.remove("active"));
}


function showPreview(icon, url, title) {
    let preview = document.createElement("div");
    preview.className = "preview-frame";

    // Create header
    let header = document.createElement("div");
    header.className = "preview-header";
    header.innerText = title || "Preview"; // Use the provided title

    // Create iframe
    let iframe = document.createElement("iframe");
    iframe.src = url;
    iframe.frameBorder = "0";

    preview.appendChild(header);
    preview.appendChild(iframe);
    document.body.appendChild(preview);

    // Apply styles using CSS
    Object.assign(preview.style, {
        position: "fixed",
        top: "50%",
        left: "50%",
        width: "80vw",
        height: "60vh",
        transform: "translate(-50%, -50%)",
        background: "rgba(0, 0, 0, 0.8)",
        padding: "10px",
        borderRadius: "8px",
        boxShadow: "0 4px 10px rgba(0, 0, 0, 0.3)",
        zIndex: "1000",
        display: "flex",
        flexDirection: "column"
    });

    Object.assign(header.style, {
        background: " #eb1c24",
        color: "#fff",
        padding: "10px",
        fontSize: "16px",
        fontWeight: "bold",
        textAlign: "center"
    });

    Object.assign(iframe.style, {
        width: "100%",
        height: "100%",
        borderRadius: "5px",
        flexGrow: "1"
    });

    // Remove preview when mouse leaves the icon
    icon.onmouseleave = () => preview.remove();
}

// Array to store references to opened windows
let openWindows = []; 
let offset = 30; // Offset for each new window

// Function to open a minimized window
function openMinimizedWindow(event, url) {
    event.preventDefault(); 

    // Check if the URL is already open
    let existingWindow = openWindows.find(win => win && !win.closed && win.url === url);
    if (existingWindow) {
        existingWindow.ref.focus(); 
        return;
    }

    let left = 100 + (openWindows.length * offset); 
    let top = 100 + (openWindows.length * offset);

    let newWin = window.open(url, '_blank', `width=400,height=400,left=${left},top=${top}`);
    
    if (newWin) {
        openWindows.push({ ref: newWin, url: url });

        // Apply green border to the corresponding image
        document.querySelectorAll(".nav-item a").forEach(anchor => {
            if (anchor.getAttribute("onclick").includes(url)) {
                let img = anchor.querySelector("img");
                if (img) img.classList.add("active");
            }
        });

        // Start monitoring closed tabs
        monitorClosedTabs();
    }
}

// Function to open a new window and remove frame
function open_new(button, url) {
    let wrapper = button.closest(".frame-wrapper");
    if (wrapper) {
        wrapper.remove();
        adjustFrames();
    }

    openMinimizedWindow(new Event("click"), url);
}

// Function to monitor and remove borders when windows close
function monitorClosedTabs() {
    let checkInterval = setInterval(() => {
        openWindows = openWindows.filter(winObj => {
            if (!winObj.ref || winObj.ref.closed) {
                // Remove active border if window is closed
                document.querySelectorAll(".nav-item a").forEach(anchor => {
                    if (anchor.getAttribute("onclick").includes(winObj.url)) {
                        let img = anchor.querySelector("img");
                        if (img) img.classList.remove("active");
                    }
                });

                return false; // Remove closed window from list
            }
            return true; // Keep open windows
        });

        // Stop checking if no windows are left
        if (openWindows.length === 0) clearInterval(checkInterval);
    }, 1000); // Check every second
}



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
    let navImages = document.querySelectorAll(".nav-item img");

    navImages.forEach(img => {
        img.addEventListener("click", function () {
            this.classList.add("active"); // Only add the class, never remove
        });
    });
});



//add Sar
// Function to open modal
function openModal() {
    document.getElementById("modalForm").style.display = "flex";
}

// Function to close modal
function closeModal() {
    document.getElementById("modalForm").style.display = "none";
}

// Function to clear individual inputs
function clearInput(inputId) {
    document.getElementById(inputId).value = "";
}

function clearAllInputs() {
    document.getElementById("title").value = "";
    document.getElementById("description").value = "";
    document.getElementById("link").value = "";

    // Clear the image preview
    document.getElementById("imagePreview").src = "";
}

// Function to preview and resize image upload
function previewImage(event, imgId) {
    const reader = new FileReader();
    reader.onload = function () {
        const img = document.getElementById(imgId);
        img.src = reader.result;
        img.style.width = "100px";  // Set the width of the preview
        img.style.height = "100px"; // Set the height of the preview
        img.style.objectFit = "cover"; // Ensure image fits well
        img.style.border = "1px solid #ddd"; // Optional border for aesthetics
        img.style.borderRadius = "8px"; // Optional rounded corners
    };
    reader.readAsDataURL(event.target.files[0]);
}


// Function to clear image preview
function clearImage(imgId) {
    document.getElementById(imgId).src = "";
}




// Open Manage Modal
function openManageModal() {
    document.getElementById("manageModal").style.display = "flex";
    loadSARProjects();  // Load SAR Projects into the modal
}

// Close Manage Modal
function closeManageModal() {
    document.getElementById("manageModal").style.display = "none";
}

// Open Edit Modal
function openEditModal() {
    document.getElementById("editModal").style.display = "flex";
}

// Close Edit Modal
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

// Load SAR Projects into the Manage Table
function loadSARProjects() {
    fetch('../main/get_sar_projects.php')  // Fetch SAR projects from the server
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            let tableBody = document.getElementById("sarProjectList");
            tableBody.innerHTML = "";  // Clear existing table content
            data.forEach(project => {
                // Dynamically add rows to the table
                let row = `<tr>
                    <td>${project.title}</td>
                    <td><a href="${project.link}" target="_blank">${project.link}</a></td>
                    <td><img src="${project.icon_image}" alt="${project.title}" width="50"></td>
                    <td>
                        <button onclick="editProject(${project.id})" class="edit-button">Edit</button>
                        <button onclick="deleteProject(${project.id})" class="delete-button">Delete</button>
                    </td>

                </tr>`;
                tableBody.innerHTML += row;  // Add new row
            });
        })
        .catch(error => console.error('Error loading projects:', error));  // Handle error
}

function deleteProject(id) {
    if (!confirm("Are you sure you want to delete this project?")) return;

    fetch('../main/delete_sar_project.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(id)}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            setTimeout(() => {
                location.reload(); // Full page reload after 1 second
            }, 1000);
        }
    })
    .catch(error => console.error('Error deleting project:', error));
}



// Open the Edit Modal and Pre-fill Data
function editProject(id) {
    fetch(`../main/get_sar_projects.php?id=${id}`) // Fetch the project's data
        .then(response => response.json())
        .then(data => {
            document.getElementById("editId").value = data.id;
            document.getElementById("editTitle").value = data.title;
            document.getElementById("editLink").value = data.link;
            document.getElementById("currentIcon").src = data.icon_image;
            openEditModal();
        })
        .catch(error => console.error("Error loading project details:", error));
}

// Submit the Edit Form
document.getElementById("editForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);

    fetch("../main/edit_sar_project.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            closeEditModal();
            loadSARProjects(); // Refresh project list
            setTimeout(() => {
                location.reload(); // Reload the page after a short delay
            }, 1000); // Delay 1 second for better UX
        }
    })
    .catch(error => console.error("Error updating project:", error));
});


function previewIcon(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('currentIcon').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}



