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

    let header = document.createElement("div");
    header.className = "preview-header";
    header.innerText = title || "Preview";

    let iframe = document.createElement("iframe");
    iframe.src = url;
    iframe.frameBorder = "0";

    preview.appendChild(header);
    preview.appendChild(iframe);
    document.body.appendChild(preview);

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
        background: "#eb1c24",
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

    let timeout;

    const removePreview = () => {
        if (preview && preview.parentNode) {
            preview.remove();
        }
    };

    const scheduleRemove = () => {
        timeout = setTimeout(removePreview, 200); // short delay
    };

    const cancelRemove = () => {
        clearTimeout(timeout);
    };

    icon.addEventListener("mouseleave", scheduleRemove);
    preview.addEventListener("mouseenter", cancelRemove);
    preview.addEventListener("mouseleave", scheduleRemove);
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



