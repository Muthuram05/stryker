// Check login status on page load
window.onload = function () {
    if (localStorage.getItem("loggedIn") !== "true") {
        window.location.href = "login.php"; // Redirect to login if not logged in
    }
};

// Function to toggle the visibility of the network dropdown
function toggleNetworkDropdown(event) {
    event.preventDefault(); // Prevent default link behavior
    const dropdown = document.getElementById('network-dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle visibility
}

// Hide dropdown if clicking outside of it
document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('network-dropdown');
    const networkLink = document.querySelector('.dropdown-toggle');

    // Check if click is outside the dropdown and the link
    if (!networkLink.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none'; // Hide dropdown if clicking outside
    }
});

document.addEventListener("DOMContentLoaded", function () {

    const contentArea = document.getElementById('content-area');
    const dropdownMenu = document.getElementById('network-dropdown');
    const loginForm = document.getElementById("loginForm");
    const errorDiv = document.getElementById("error");

    // Check login status on every page
    const isloggedIn = localStorage.getItem("loggedIn")
    if (!isloggedIn) {
        window.location.href = "login.php"; // Redirect to login if not logged in
    }

    // Handle login form submission
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            // Validate credentials
            if (username === "Stryker" && password === "mAIstroStream") {
                localStorage.setItem("loggedIn", "true"); // Store login state in localStorage
                window.location.href = "index.php"; // Redirect to index1.html
            } else {
                errorDiv.textContent = "Invalid username or password."; // Display error
                errorDiv.style.display = "block"; // Show the error message
            }
        });
    }




    // Function to remove the 'active' class from all sidebar links
    function removeClass(selectedContent) {
        document.querySelectorAll('.section-left a').forEach(link => {
            if (link.getAttribute('data-content') !== selectedContent) {
                link.classList.remove("active");
            }
        });
    }

    // Handle dropdown visibility toggle
    const networkLink = document.querySelector('.dropdown-toggle');
    networkLink.addEventListener('click', toggleNetworkDropdown);
});
document.addEventListener('DOMContentLoaded', function () {
    // Initial call to update MAC addresses
    updateMacAddresses();

    // Update every second
    setInterval(updateMacAddresses, 1000);
});

function calculateSpeed() {
    const imageUrl = 'https://www.gstatic.com/webp/gallery/1.jpg'; // Use a known working image URL
    const startTime = Date.now();
    const img = new Image();

    img.src = imageUrl + '?rand=' + Math.random(); // Avoid caching

    img.onload = function () {
        const endTime = Date.now();
        const durationInSeconds = (endTime - startTime) / 1000; // Convert ms to seconds
        const imageSizeInBytes = img.naturalWidth * img.naturalHeight * 4; // Approximate size (RGBA)

        const speedInMbps = (imageSizeInBytes * 8) / (durationInSeconds * 1000000); // Convert to Mbps
        document.getElementById('speed').innerText = `${speedInMbps.toFixed(2)} Mbps`;
        console.log(`Image loaded in ${durationInSeconds} seconds. Speed: ${speedInMbps.toFixed(2)} Mbps`);
    };

    img.onerror = function () {
        console.error('Could not connect the internet.');
        document.getElementById('speed').innerText = 'Could not connect the internet.';
    };

    setTimeout(function () {
        if (!img.complete) {
            document.getElementById('speed').innerText = 'Loading timed out.';
            console.warn('Image loading timed out.');
        }
    }, 10000); // Increase timeout to 10 seconds for testing
}

// Start both functions on window load


document.addEventListener('DOMContentLoaded', function () {
    // Initial call to update information
    updateMacAddresses();
    updateMemoryUtilization();
    calculateSpeed();

    // Update every 5 seconds
    setInterval(updateMacAddresses, 5000);
    setInterval(updateMemoryUtilization, 5000);
});

function updateMacAddresses() {
    fetch('eth0.php')
        .then(response => response.text())
        .then(macAddress => {
            document.getElementById('mac0').textContent = macAddress || 'Unknown';
        })
        .catch(error => {
            console.error('Error fetching eth0 MAC address:', error);
            document.getElementById('mac0').textContent = 'Error fetching MAC';
        });

    fetch('eth1.php')
        .then(response => response.text())
        .then(macAddress => {
            document.getElementById('mac1').textContent = macAddress || 'Unknown';
        })
        .catch(error => {
            console.error('Error fetching eth1 MAC address:', error);
            document.getElementById('mac1').textContent = 'Error fetching MAC';
        });
}

function updateMemoryUtilization() {
    fetch('memory_utilization.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('memory-utilization').innerHTML = data + '%';
        })
        .catch(error => {
            console.error('Error fetching memory utilization:', error);
        });
}
window.onload = function () {
    updateMacAddresses()
    calculateSpeed()
    updateMemoryUtilization();
};



