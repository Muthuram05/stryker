<?php
function getJsonValue($filePath, $key)
{
    // Check if the file exists
    if (!file_exists($filePath)) {
        return "Error: File does not exist.";
    }

    // Read the JSON file content
    $jsonContent = file_get_contents($filePath);

    // Decode JSON into associative array
    $data = json_decode($jsonContent, true);

    // Check if decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Error: Invalid JSON format.";
    }

    // Check if the key exists in the array
    if (!array_key_exists($key, $data)) {
        return "Error: Key does not exist in the JSON file.";
    }

    // Return the value of the specified key
    return $data[$key];
}

// Define the path to the JSON configuration file
$filePath = "/etc/configuration.json";

// Get the value for key 2
$res = getJsonValue($filePath, 2);

// Initialize variables for radio button states
$s0 = $s1 = $s2 = $s3 = $s4 = "";

// Set the appropriate variable based on the value of $res
if ((int) $res === 0) {
    $s0 = "checked";
} elseif ((int) $res === 1) {
    $s1 = "checked";
} elseif ((int) $res === 2) {
    $s2 = "checked";
} elseif ((int) $res === 3) {
    $s3 = "checked";
} elseif ((int) $res === 4) {
    $s4 = "checked";
}

// Read the values for keys 5, 8, 11, and 14
$wifiSettings = [
    5 => getJsonValue($filePath, 5),
    8 => getJsonValue($filePath, 8),
    11 => getJsonValue($filePath, 11),
    14 => getJsonValue($filePath, 14),
];

// Initialize the checked state variables
$checkedStates = [];
foreach ($wifiSettings as $key => $value) {
    $checkedStates[$key] = (int) $value === 1 ? "checked" : "";
}

// You can now use $s0, $s1, $s2, $s3, $s4, and $checkedStates in your HTML
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stryker</title>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/wifi.css">
    <script src="script.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/jquery-3.7.1.min.js"></script>
    <style>
        .button {
            background-color: #DB9704;
            border: none;
        }

        .button:hover {
            background-color: #DB9704;
            border: none;

        }

        button:focus {
            background-color: #DB9704;
            border: none;
        }

        /* Base styles for the toggle switch */
        .wifi-toggle {
            display: inline-block;
            position: relative;
        }

        /* Hide the default checkbox */
        .wifi-toggle .form-check-input {
            display: none;
            /* Hide the original checkbox */
        }

        /* Create the custom toggle switch */
        .wifi-toggle .form-check-label {
            display: block;
            width: 60px;
            /* Width of the toggle */
            height: 30px;
            /* Height of the toggle */
            background-color: #5B5B5B;
            /* Background color when inactive */
            border-radius: 15px;
            /* Rounded corners */
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s;
            /* Smooth background transition */
        }

        /* Toggle switch when checked */
        .wifi-toggle .form-check-input:checked+.form-check-label {

            background-color: #DBA92C;
            /* Background color when active */
        }

        /* The toggle button (the circle) */
        .wifi-toggle .form-check-label::after {
            content: '';
            display: block;
            width: 24px;
            /* Width of the toggle knob */
            height: 26px;
            /* Height of the toggle knob */
            background: white;
            /* Color of the knob */
            border-radius: 50%;
            /* Make it circular */
            position: absolute;
            top: 2px;
            /* Position it vertically centered */
            left: 2px;
            /* Position it from the left */
            transition: transform 0.3s;
            /* Smooth transition for the knob */
        }

        /* Move the knob to the right when checked */
        .wifi-toggle .form-check-input:checked+.form-check-label::after {
            transform: translateX(30px);
            /* Move the knob to the right */
        }

        /* Focus styles for accessibility */
        .wifi-toggle .form-check-input:focus+.form-check-label {
            box-shadow: 0 0 0 0.25rem rgba(219, 169, 44, 0.25);
            /* Focus effect */
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
            <a href="index.php" class="text-decoration-none py-4 px-3  text-white" data-content="">Home</a>
            <a href="#" class="text-decoration-none py-4 px-3 text-white dropdown-toggle active"
                data-content="network">Network</a>

            <!-- Dropdown for Network Tabs -->
            <ul id="network-dropdown" class="dropdown" style="display: none;">
                <li><a href="wifi.php" class="text-decoration-none active" data-content="wifi">WiFi</a></li>
                <li><a href="video.php" class="text-decoration-none" data-content="video">Video</a></li>

            </ul>

            <a href="logs.php" class="text-decoration-none py-4 px-3 text-white" data-content="logs">Log</a>
            <a href="upgrade.php" class="text-decoration-none py-4 px-3 text-white" data-content="upgrade">Upgrade</a>
            <a href="system-info.php" class="text-decoration-none py-4 px-3 text-white"
                data-content="system-info">System Info</a>
            <a href="help.php" class="text-decoration-none py-4 px-3 text-white" data-content="help">Help</a>
            <a href="restart.php" class="text-decoration-none py-4 px-3 text-white" data-content="restart">Restart</a>
        </div>

        <div class="py-4 px-3" style="width:100%">
            <div class="d-flex t-container align-items-center">
                <!-- First Frame: WiFi selection -->

                <div class="frame t-height" id="networkForm2">

                    <h3>Hospital Network Connection</h3>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="network1" id="disable" value="disable"
                                <?php echo "$s0"; ?> onchange="toggleFields()"
                                onclick="loadWifiSettings('frame6', '1')">
                            <label class="form-check-label" for="disable">Disable</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="network1" id="wifi1a1" value="WiFi 1A"
                                <?php echo "$s1"; ?> onchange="toggleFields()"
                                onclick="loadWifiSettings('frame2', '1')">
                            <label class="form-check-label" for="wifi1a1">WiFi 1A</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="network1" id="wifi1b1" value="WiFi 1B"
                                <?php echo "$s2"; ?> onchange="toggleFields()"
                                onclick="loadWifiSettings('frame3', '1')">
                            <label class="form-check-label" for="wifi1b1">WiFi 1B</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="network1" id="wifi2a1" value="WiFi 2A"
                                <?php echo "$s3"; ?> onclick="loadWifiSettings('frame4', '1')">
                            <label class="form-check-label" for="wifi2a1">WiFi 2A</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="network1" id="wifi2b1" value="WiFi 2B"
                                <?php echo "$s4"; ?> onclick="loadWifiSettings('frame5', '1')">
                            <label class="form-check-label" for="wifi2b1">WiFi 2B</label>
                        </div>
                    </div>

                    <table>
                        <tr>
                            <td><label for="ssidInput" class="form-label">SSID</label></td>
                            <td><input type="text" class="form-control" id="ssidInput" style="width: 350px;"
                                    placeholder="Enter SSID"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="passwordInput" class="form-label">Password</label></td>
                            <td><input type="text" class="form-control" id="passwordInput" placeholder="Enter Password"
                                    minlength="8" required></td>
                        </tr>
                        <td><button type="button" class="btn button" onclick="enableNetwork()"
                                id="enableButton">Save</button></td>
                    </table>


                </div>
            </div>

            <div class="d-flex frame-align">
                <div class="frame2 check" id="wifiSettings1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>WiFi 1A 5GHz</h3>
                        <div class="form-check form-switch wifi-toggle">
                            <input class="form-check-input" type="checkbox" id="wifiToggle1"
                                onchange="toggleWifiFields(this, '1')" <?= $checkedStates[5] ?>>
                            <label class="form-check-label" for="wifiToggle1"></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="wifiNameInput1" class="form-label">WiFi Name</label>
                        <input type="text" class="form-control" id="wifiNameInput1" placeholder="Enter WiFi Name"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="wifiPasswordInput1" class="form-label">WiFi Password</label>
                        <input type="text" class="form-control" id="wifiPasswordInput1"
                            placeholder="Enter WiFi Password" disabled>
                    </div>
                    <button type="button" class="btn button mt-3" onclick="enableHotspot('1')">Save</button>
                </div>
                <div class="frame3 check" id="wifiSettings2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>WiFi 1B 2.4GHz</h3>
                        <div class="form-check form-switch wifi-toggle">
                            <input class="form-check-input" type="checkbox" id="wifiToggle2"
                                onchange="toggleWifiFields(this, '2')" <?= $checkedStates[8] ?>>
                            <label class="form-check-label" for="wifiToggle2"></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="wifiNameInput2" class="form-label">WiFi Name</label>
                        <input type="text" class="form-control" id="wifiNameInput2" placeholder="Enter WiFi Name"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="wifiPasswordInput2" class="form-label">WiFi Password</label>
                        <input type="text" class="form-control" id="wifiPasswordInput2"
                            placeholder="Enter WiFi Password" disabled>
                    </div>
                    <button type="button" class="btn button mt-3" onclick="enableHotspot('2')">Save</button>
                </div>
                <div class="frame4 check" id="wifiSettings3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>WiFi 2A 5GHz</h3>
                        <div class="form-check form-switch wifi-toggle">
                            <input class="form-check-input" type="checkbox" id="wifiToggle3"
                                onchange="toggleWifiFields(this, '3')" <?= $checkedStates[11] ?>>
                            <label class="form-check-label" for="wifiToggle3"></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="wifiNameInput3" class="form-label">WiFi Name</label>
                        <input type="text" class="form-control" id="wifiNameInput3" placeholder="Enter WiFi Name"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="wifiPasswordInput3" class="form-label">WiFi Password</label>
                        <input type="text" class="form-control" id="wifiPasswordInput3"
                            placeholder="Enter WiFi Password" disabled>
                    </div>
                    <button type="button" class="btn button mt-3" onclick="enableHotspot('3')">Save</button>
                </div>
                <div class="frame5 check" id="wifiSettings4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>WiFi 2B 2.4GHz</h3>
                        <div class="form-check form-switch wifi-toggle">
                            <input class="form-check-input" type="checkbox" id="wifiToggle4"
                                onchange="toggleWifiFields(this, '4')" <?= $checkedStates[14] ?>>
                            <label class="form-check-label" for="wifiToggle4"></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="wifiNameInput4" class="form-label">WiFi Name</label>
                        <input type="text" class="form-control" id="wifiNameInput4" placeholder="Enter WiFi Name"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="wifiPasswordInput4" class="form-label">WiFi Password</label>
                        <input type="text" class="form-control" id="wifiPasswordInput4"
                            placeholder="Enter WiFi Password" disabled>
                    </div>
                    <button type="button" class="btn button mt-3" onclick="enableHotspot('4')">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadData() {
            const a = document.querySelector("input[name='network1']:checked").value
            if (a === 'disable') {
                loadWifiSettings('frame6')
            } else if (a === "WiFi 1A") {
                loadWifiSettings('frame2')
            } else if (a === "WiFi 1B") {
                loadWifiSettings('frame3')
            } else if (a === "WiFi 2A") {
                loadWifiSettings('frame4')
            } else if (a === "WiFi 2B") {
                loadWifiSettings('frame5')
            }
        }

        function loadWifiSettings(id) {
            const allLinks = document.querySelectorAll('.check');
            allLinks.forEach(link => link.classList.remove('Disable'));
            const elements = document.getElementsByClassName(id);
            if (elements.length > 0) {
                elements[0].classList.add("Disable");
            }
        }

        // Function to enable or disable WiFi fields based on the toggle state
        function toggleWifiFields(checkbox, id) {
            const wifiNameInput = document.getElementById(`wifiNameInput${id}`);
            const wifiPasswordInput = document.getElementById(`wifiPasswordInput${id}`);

            const isChecked = checkbox.checked;
            wifiNameInput.disabled = !isChecked;
            wifiPasswordInput.disabled = !isChecked;

            if (!isChecked) {
                wifiNameInput.value = ""; // Clear value
                wifiPasswordInput.value = ""; // Clear value
            }

            localStorage.setItem(`wifiToggle${id}`, isChecked.toString()); // Store state in local storage
        }

        // Function to initialize toggle states on page load
        function initializeWifiFields() {
            for (let i = 1; i <= 4; i++) {
                const toggleCheckbox = document.getElementById(`wifiToggle${i}`);
                const toggleState = localStorage.getItem(`wifiToggle${i}`);

                toggleCheckbox.checked = toggleState === 'true';
                toggleWifiFields(toggleCheckbox, i);
            }
        }

        // Function to enable network settings
        function enableNetwork() {
            const selectedNetwork = document.querySelector('input[name="network1"]:checked').value;
            console.log("Selected Network:", selectedNetwork);

            const ssid = document.getElementById("ssidInput");
            const password = document.getElementById("passwordInput");

            // Save the selected network option to localStorage
            localStorage.setItem("selectedNetwork", selectedNetwork);

            // Check if the "disable" option is selected
            if (selectedNetwork === 'disable') {
                // Disable SSID and Password fields
                ssid.disabled = true;
                password.disabled = true;

                // Send data with empty SSID and Password
                sendData({
                    wifiKey: 0,
                    ssid: '',
                    password: ''
                });
                return; // Exit the function
            }

            // Enable SSID and Password fields for all other options
            ssid.disabled = false;
            password.disabled = false;

            // Validate password length
            if (password.value.length < 8) {
                // Show popup for invalid password length
                document.getElementById("popup-message").textContent = "Password must be at least 8 characters long";
                document.getElementById("popup").style.display = "block";
                document.getElementById("popup-overlay").style.display = "block";

                // Clear the SSID and Password fields
                ssid.value = '';
                password.value = '';
                return;
            }

            // Define WiFi key mapping
            const wifiKeyMap = {
                'WiFi 1A': '5',
                'WiFi 1B': '8',
                'WiFi 2A': '11',
                'WiFi 2B': '14',
                'default': '1'
            };

            const wifiKey = wifiKeyMap[selectedNetwork] || wifiKeyMap['default'];
            console.log("WiFi Key:", wifiKey);

            // Send data to the server
            sendData({
                wifiKey: wifiKey,
                ssid: ssid.value,
                password: password.value
            });

            // Clear inputs after submission
            ssid.value = '';
            password.value = '';
        }


        // Function to initialize network settings on page load
        function initializeNetworkSettings() {
            const savedNetwork = localStorage.getItem("selectedNetwork");
            const ssid = document.getElementById("ssidInput");
            const password = document.getElementById("passwordInput");

            if (savedNetwork === 'disable') {
                document.querySelector('input[name="network1"][value="disable"]').checked = true;
                ssid.disabled = true;
                password.disabled = true;
            } else if (savedNetwork) {
                document.querySelector(`input[name="network1"][value="${savedNetwork}"]`).checked = true;
                ssid.disabled = false;
                password.disabled = false;
            }
        }

        // Function to send data to the server
        function sendData(data) {
            fetch('wifi_settings.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result); // Log the result to check what is returned
                    try {
                        const json = JSON.parse(result); // Attempt to parse JSON
                        document.getElementById("popup-message").textContent = json.message;
                        document.getElementById("popup").style.display = "block";
                        document.getElementById("popup-overlay").style.display = "block";
                        // alert(json.message);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        // Function to enable hotspot settings
        function enableHotspot(id) {
            const wifiNameInput = document.getElementById(`wifiNameInput${id}`);
            const wifiPasswordInput = document.getElementById(`wifiPasswordInput${id}`);

            let wifiNameValue = wifiNameInput.disabled ? '0' : wifiNameInput.value;
            let wifiPasswordValue = wifiPasswordInput.disabled ? '0' : wifiPasswordInput.value;

            if (!wifiPasswordInput.disabled && wifiPasswordValue.length < 8) {
                // alert("Password must be at least 8 characters long");
                document.getElementById("popup-message").textContent = "Password must be at least 8 characters long";
                document.getElementById("popup").style.display = "block";
                document.getElementById("popup-overlay").style.display = "block";
                wifiPasswordInput.value = "";
                return;
            }

            if (wifiNameValue.trim() === '') {
                // alert("WiFi Name cannot be empty");
                document.getElementById("popup-message").textContent = "WiFi Name cannot be empty";
                document.getElementById("popup").style.display = "block";
                document.getElementById("popup-overlay").style.display = "block";
                return;
            }

            const action = wifiNameInput.disabled ? "disable" : "enable";

            if (action === "disable") {
                wifiNameValue = '0';
                wifiPasswordValue = '0';
            }

            const data = {
                wifiName: wifiNameValue,
                wifiPassword: wifiPasswordValue,
                action: action,
                id: id
            };

            fetch('wifi_script.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // alert(data.status);
                    document.getElementById("popup-message").textContent = data.status;
                    document.getElementById("popup").style.display = "block";
                    document.getElementById("popup-overlay").style.display = "block";
                    if (data.status === 'success') {
                        if (action === "enable") {
                            wifiNameInput.value = "";
                            wifiPasswordInput.value = "";
                        }
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert("An error occurred while saving the settings. Please try again.");
                });
        }

        // Function to restore toggle states
        function restoreToggleStates() {
            for (let id = 1; id <= 4; id++) {
                const toggle = document.getElementById(`wifiToggle${id}`);
                toggle.checked = localStorage.getItem(`wifiToggle${id}`) === 'true';
                toggleWifiFields(toggle, id);
            }
        }

        // Call restore toggle states on page load
        function toggleFields() {
            const disableRadio = document.getElementById('disable'); // The "Disable" radio button
            const ssidField = document.getElementById("ssidInput"); // SSID input field
            const passwordField = document.getElementById("passwordInput"); // Password input field

            if (disableRadio.checked) {
                // Disable SSID and Password fields
                ssidField.disabled = true;
                passwordField.disabled = true;

                // Clear the fields
                ssidField.value = '';
                passwordField.value = '';
            } else {
                // Enable SSID and Password fields
                ssidField.disabled = false;
                passwordField.disabled = false;
            }
        }

        // Add event listeners to radio buttons to trigger toggleFields
        document.querySelectorAll('input[name="network1"]').forEach(radio => {
            radio.addEventListener('change', toggleFields);
        });

        // Call toggleFields on page load to set the correct initial state
        window.onload = function() {
            initializeWifiFields();
            restoreToggleStates();
            initializeNetworkSettings();
            loadData();
            toggleFields(); // Ensure fields are toggled correctly on load
        };

        // Add event listeners to radio buttons to trigger toggleFields
        document.querySelectorAll('input[name="network1"]').forEach(radio => {
            radio.addEventListener('change', toggleFields);
        });
        document.getElementById("close-popup").onclick = function() {
            document.getElementById("popup").style.display = "none";

            document.getElementById("popup-overlay").style.display = "none";
            location.reload();
        };
    </script>

</body>

</html>