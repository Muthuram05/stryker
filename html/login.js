
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const errorDiv = document.getElementById("error");
    //const isloggedIn=localStorage.getItem("loggedIn") 

    //  if (isloggedIn) {
    //window.location.href = "index1.html"; // Redirect to login if not logged in
    // }
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
});

