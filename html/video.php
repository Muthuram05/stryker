<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stryker</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/wifi.css">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/jquery-3.7.1.min.js"></script>
    <style>
        .fm {
            width: 800px;

            margin: 0 auto;
            /* Center the form container */
            display: flex;
            flex-direction: column;
            /* Stack form elements vertically */
            align-items: center;
            /* Align child elements to the center */
        }

        .button {
            color: black;
            /* Text color */
            background-color: #d7932b;
            /* Default background color */
            border: none;
            /* Optional: Remove border */
            padding: 10px 20px;
            /* Optional: Add padding for better appearance */
            font-size: 16px;
            /* Optional: Set font size */
            cursor: pointer;
            /* Change cursor to pointer on hover */
        }

        .button:hover {
            background: #ffcd7f;
            /* Background color on hover */
            color: black;
            /* Change text color on hover */
        }


        body {
            background-color: #FEF8F8;
        }

        .card-header {
            background-color: #9c9c9c !important;
        }

        
    </style>

</head>

<body>
    <div class="bg-info fs-1 d-flex py-4 px-3 text-black align-items-center border-bottom border-4 border-dark header">
        <img src="assets/logo.png" alt="Logo" class="header-logo me-3" style="height: 60px;">
        <!-- Adjust height as needed -->
    </div>
    <div class="overlay" id="popup-overlay"></div>
    <div class="popup" id="popup">
        <div class="popup-content">
            <p id="popup-message">Configuration saved successfully!</p>
            <button id="close-popup">Close</button>
        </div>
    </div>
    <div class="d-flex section">
        <div class="d-flex flex-column w-200 bg-dark section-left">
            <a href="index.php" class="text-decoration-none py-4 px-3 text-white">Home</a>
            <a href="#" class="text-decoration-none py-4 px-3 text-white dropdown-toggle active">Network</a>
            <!-- Network Tabs -->
            <ul id="network-dropdown" class="dropdown" style="display: none;">
                <li><a href="wifi.php" class="text-decoration-none">WiFi</a></li>
                <li><a href="video.php" class="text-decoration-none active">Video</a></li>
            </ul>
            <a href="logs.php" class="text-decoration-none py-4 px-3 text-white">Log</a>
            <a href="upgrade.php" class="text-decoration-none py-4 px-3 text-white">Upgrade</a>
            <a href="system-info.php" class="text-decoration-none py-4 px-3 text-white">System Info</a>
            <a href="help.php" class="text-decoration-none py-4 px-3 text-white">Help</a>
            <a href="restart.php" class="text-decoration-none py-4 px-3 text-white">Restart</a>
        </div>
        <form class="fm" id="networkForm">
            <div class="container mt-5">
                <div class="card mb-2">
                    <div class="card-header">
                        <h5>UDP Stream HDMI 1</h5>
                    </div>

                    <!-- UDP Stream B1 -->
                    <div class="card-body ">
                        <table>
                            <tr>
                                <td>
                                    <label for="ipAddressB1" class="form-label d-flex mt-2">IP Address</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control mt-2" id="ipAddressB1"
                                        placeholder="Enter the IP address">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="portB1" class="form-label d-flex mt-2">Port</label>
                                </td>
                                <td>
                                    <input type="number" class="form-control mt-2" id="portB1"
                                        placeholder="Enter the port" min="1000" max="6000">
                                </td>
                            </tr>
                        </table>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="loopout">
                            <label class="form-check-label" for="loopout">Loopout</label>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <h6>Output Interface</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="wifi1A">
                                <label class="form-check-label" for="wifi1A">wifi 1A</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="wifi2A">
                                <label class="form-check-label" for="wifi2A">wifi 2A</label>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- UDP Stream B2 -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>UDP Stream HDMI2</h5>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td>
                                    <label for="ipAddressB2" class="form-label d-flex mt-2">IP Address</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control mt-2" id="ipAddressB2"
                                        placeholder="Enter the IP address">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="portB2" class="form-label d-flex mt-2">Port</label>
                                </td>
                                <td>
                                    <input type="number" class="form-control mt-2" id="portB2"
                                        placeholder="Enter the port" min="1000" max="6000">
                                </td>
                            </tr>
                        </table>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="loopoutb2">
                            <label class="form-check-label" for="loopoutb2">Loopout</label>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <h6>Output Interface</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="wifi1B">
                                <label class="form-check-label" for="wifi1B">wifi 1A</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="wifi2B">
                                <label class="form-check-label" for="wifi2B">wifi 2A</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn  button">Save</button>
                    <button type="button" class="btn button" onclick="showAdvancedSettings()">Advanced
                        Settings</button>
                </div>

            </div>
        </form>
    </div>
    <script>
        function submitForm() {
            const ipAddressB1 = document.getElementById('ipAddressB1').value;
            const portB1 = document.getElementById('portB1').value;
            const ipAddressB2 = document.getElementById('ipAddressB2').value;
            const portB2 = document.getElementById('portB2').value;

            // Get the checkbox elements
            const wifi1AChecked = document.getElementById('wifi1A').checked;
            const wifi2AChecked = document.getElementById('wifi2A').checked;
            const wifi1BChecked = document.getElementById('wifi1B').checked;
            const wifi2BChecked = document.getElementById('wifi2B').checked;
            const loopoutChecked = document.getElementById('loopout').checked; // Board 1 loopout
            const loopoutb2Checked = document.getElementById('loopoutb2').checked; // Board 2 loopout

            // Initialize the configuration object
            const config = {
                "ipAddressB1": ipAddressB1,
                "portB1": portB1,
                "ipAddressB2": ipAddressB2,
                "portB2": portB2,
                "26": "0", // Default value for Board 1 WiFi
                "27": "0", // Default value for Board 2 WiFi
                "24": loopoutChecked ? "1" : "0", // Set based on loopout check
                "25": loopoutb2Checked ? "1" : "0", // Set based on loopoutb2 check
                "17": "1", // Set value 1 for key 17
                "18": "1", // Set value 1 for key 18
                "21": "0" // Default value for key 21
            };

            // Determine the value for Board 1 WiFi
            if (wifi1AChecked && wifi2AChecked) {
                config["26"] = "3";
            } else if (wifi1AChecked) {
                config["26"] = "1";
            } else if (wifi2AChecked) {
                config["26"] = "2";
            }

            // Determine the value for Board 2 WiFi
            if (wifi1BChecked && wifi2BChecked) {
                config["27"] = "3";
            } else if (wifi1BChecked) {
                config["27"] = "1";
            } else if (wifi2BChecked) {
                config["27"] = "2";
            }

            // Set key 21 based on loopout checks
            if (!loopoutChecked) {
                config["21"] = 0; // If loopout is not checked, set 21 to 0
            }

            // Send the data to the PHP script
            fetch('modify.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(config)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Assuming the response contains a 'success' key
                    // Show the success popup
                    document.getElementById("popup-message").textContent = data.message;
                    document.getElementById("popup").style.display = "block";
                    document.getElementById("popup-overlay").style.display = "block";




                    // alert(data.message);
                    //  document.getElementById("popup").innerHTML="kjgkdhgkj"
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        document.getElementById('networkForm').addEventListener('submit', function(event) {
            event.preventDefault();
            submitForm();
        })

        function showAdvancedSettings() {
            window.location.href = 'advance_setting.php';

        }
    </script>
    <script>
        // Close the popup when the user clicks the "Close" button
        document.getElementById("close-popup").onclick = function() {
            document.getElementById("popup").style.display = "none";

            document.getElementById("popup-overlay").style.display = "none";
            location.reload();
        };
    </script>
</body>

</html>