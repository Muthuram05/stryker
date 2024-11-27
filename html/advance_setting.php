<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stryker</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <script src="assets/jquery-3.7.1.min.js"></script>
    <script src="script.js"></script>

    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <style>
        .container {
            background-color: #ddab41;
            padding: 20px;
            border-radius: 5px;
            max-width: 500px;

            margin: auto;
        }

        body {
            background-color: #fff6f7;
        }

        label {
            color: black;
            font-weight: bold;
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

        .form-control {
            color: black;
            font-weight: bold;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 5px;
            color: white;
            z-index: 1000;
            width: 400px;
            /* Set the width of the popup */
        }

        .popup .popup-content {
            background-color: #333;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            /* Make text bold */
            color: white;
            /* Ensure text color is white */
        }

        .popup button {
            margin-top: 15px;
            background-color: #d7932b;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            color: black;
        }

        .popup button:hover {
            background-color: #ffcd7f;
        }

        /* Background overlay for the popup */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>

<body>
    <div class="bg-info fs-1 d-flex py-4 px-3 text-black align-items-center border-bottom border-4 border-dark header">
        <img src="assets/logo.png" alt="Logo" class="header-logo me-3" style="height: 60px;">
        <!-- Adjust height as needed -->
    </div>

    <div class="d-flex section">
        <div class="d-flex flex-column w-200 bg-dark section-left">
            <a href="index.php" class="text-decoration-none py-4 px-3 text-white">Home</a>
            <a href="#" class="text-decoration-none py-4 px-3 text-white dropdown-toggle active">Network</a>
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
        <div class="container mt-5">
            <h2>Advanced Settings</h2>
            <form id="advancedSettingsForm" method="POST">
                <div class="form-group">
                    <label for="encoder">Encoder:</label>
                    <select class="form-control" id="encoder" name="encoder">
                        <option value="h264">H264</option>
                        <option value="h265">H265</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="profile">Profile:</label>
                    <select class="form-control" id="profile" name="profile">

                    </select>
                </div>

                <div class="form-group">
                    <label for="controlRate">Control Rate:</label>
                    <select class="form-control" id="controlRate" name="controlRate">
                        <option value="low-latency">Low Latency</option>
                        <option value="variable">Variable</option>
                        <option value="constant">Constant</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rescaleOutput1">HDMI loopout1</label>
                    <select class="form-control" id="rescaleOutput1" name="rescaleOutput1">
                        <option value="1280x720p">1280x720p</option>
                        <option value="1920x1080p">1920x1080p</option>
                        <option value="3840x2160p">3840x2160p</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rescaleOutput2">HDMI loopout2</label>
                    <select class="form-control" id="rescaleOutput2" name="rescaleOutput2">
                        <option value="1280x720p">1280x720p</option>
                        <option value="1920x1080p">1920x1080p</option>
                        <option value="3840x2160p">3840x2160p</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="bitrate">Bitrate:</label>
                    <input type="number" class="form-control" id="bitrate" min="1000" max="6000" name="bitrate"
                        placeholder="Enter bitrate" required>
                </div>
                <div>
                    <div>
                        <button type="submit" class="btn mt-3 button" name="action" value="enable">Save</button>
                        <!-- <button type="submit" class="btn mt-3 button" name="action" value="disable">Disable</button> -->
                    </div>



            </form>
            <!-- Popup and Overlay -->
            <div class="overlay" id="popup-overlay"></div>
            <div class="popup" id="popup">
                <div class="popup-content">
                    <p id="popup-message">Configuration saved successfully!</p>
                    <button id="close-popup">Close</button>
                </div>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Specify the path to the configuration file
                    $configFile = '/etc/configuration.json';

                    // Load the existing configuration if the file exists
                    if (file_exists($configFile)) {
                        $configData = json_decode(file_get_contents($configFile), true);
                    } else {
                        // Initialize as an empty array if the file does not exist
                        $configData = [];
                    }

                    // Check if the disable button was clicked
                    if (isset($_POST['action']) && $_POST['action'] === 'disable') {
                        // Set values to "0" as strings and reset other values
                        $configData["28"] = "0";
                        $configData["29"] = "0";
                        $configData["30"] = "0";
                        $configData["31"] = "0";
                        $configData["32"] = "0";
                        $configData["33"] = "0"; // For HDMI Loopout 1
                        $configData["34"] = "0"; // For HDMI Loopout 2
                    } else {
                        // Set "28" to "1" as a string for enabling
                        $configData["28"] = "1";

                        // Get the posted values from the form as strings
                        $configData["32"] = (string) $_POST['encoder'];
                        $configData["29"] = (string) $_POST['profile'];
                        $configData["30"] = (string) $_POST['controlRate'];
                        $configData["31"] = (string) $_POST['bitrate'];

                        // Map resolutions to respective values as strings
                        $rescaleMapping = [
                            '1280x720p' => "1",
                            '1920x1080p' => "2",
                            '3840x2160p' => "3"
                        ];

                        // Update HDMI loopout resolutions as strings
                        $configData["33"] = $rescaleMapping[$_POST['rescaleOutput1']] ?? "0"; // HDMI Loopout 1
                        $configData["34"] = $rescaleMapping[$_POST['rescaleOutput2']] ?? "0"; // HDMI Loopout 2
                    }

                    if (file_put_contents($configFile, json_encode($configData, JSON_PRETTY_PRINT))) {
                        echo '<script>
                        $(document).ready(function() {
                            $("#popup-message").text("Configuration saved successfully!");
                            $("#popup").fadeIn();
                            $("#popup-overlay").fadeIn();
                        });
                    </script>';
                    } else {
                        echo '<script>
                        $(document).ready(function() {
                            $("#popup-message").text("Failed to save configuration.");
                            $("#popup").fadeIn();
                            $("#popup-overlay").fadeIn();
                        });
                    </script>';
                    }
                }
                ?>
            </div>
            <script>
                $(document).ready(function() {
                    $('#encoder').change(function() {
                        var selectedEncoder = $(this).val();
                        var profileSelect = $('#profile');

                        // Clear existing options
                        profileSelect.empty();

                        if (selectedEncoder === 'h265') {
                            // Add only 'High' option for H264
                            profileSelect.append('<option value="main">Main</option>');
                        } else {
                            // Add all options for H264
                            profileSelect.append('<option value="baseline">Baseline</option>');
                            profileSelect.append('<option value="main">Main</option>');
                            profileSelect.append('<option value="high">High</option>');
                        }
                    });

                    // Trigger change event to initialize options based on default selection
                    $('#encoder').change();
                });
            </script>

        </div>
        <script>
            // Close the popup when the user clicks the "Close" button
            document.getElementById("close-popup").onclick = function() {
                document.getElementById("popup").style.display = "none";
                document.getElementById("popup-overlay").style.display = "none";
            };
        </script>
</body>

</html>