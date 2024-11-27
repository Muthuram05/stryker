<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Define the target directories for each board
    $targetDirBoard1 = '/mnt/';
    $targetDirBoard2 = '/etc/';
    $configFilePath = '/etc/configuration.json';

    // Function to handle file uploads
    function handleFileUpload($fileInputName, $targetDir, $boardName, $configKey)
    {
        global $configFilePath;

        error_log(print_r($_FILES, true));


        if (isset($_FILES[$fileInputName])) {
            $fileName = basename($_FILES[$fileInputName]['name']);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Check file type
            if ($fileType !== 'swu') {
                // echo "Error: Only .swu files are allowed for $boardName.<br>";
                echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        document.getElementById('popup-message').textContent = ' Only .swu files are allowed for $boardName.';
                        document.getElementById('popup').style.display = 'block';
                    });
                </script>";
                return false;
            }

            // Check for upload errors
            if ($_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
                echo "Error for $boardName: " . $_FILES[$fileInputName]['error'] . "<br>";
                return false;
            }
            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFilePath)) {

                // echo "The file for $boardName has been uploaded successfully.<br>";
                echo "
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('popup-message').textContent = 'The file for $boardName has been uploaded successfully.';
            document.getElementById('popup').style.display = 'block';
        });
    </script>";
                updateConfigurationFile($configKey, $targetFilePath);
                return true; // Indicate successful upload
            } else {
                error_log("Failed to move uploaded file for $boardName: " . print_r($_FILES[$fileInputName], true));
                echo "Error: There was a problem uploading the file for $boardName.<br>";
                return false;
            }
        }
        return false; // No file uploaded
        // error_log(print_r($_FILES, true)); // Log the $_FILES array

    }

    // Function to update the configuration file
    function updateConfigurationFile($key, $filePath)
    {
        global $configFilePath;

        // Read the existing configuration file
        if (file_exists($configFilePath)) {
            $configData = json_decode(file_get_contents($configFilePath), true);

            // Update the specified key with the new file path
            $configData[$key] = $filePath;

            // Write the updated data back to the configuration file
            if (file_put_contents($configFilePath, json_encode($configData, JSON_PRETTY_PRINT))) {
                // echo "Configuration file updated successfully.<br>";
                echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        document.getElementById('popup-message').textContent = 'Configuration file updated successfully.';
                        document.getElementById('popup').style.display = 'block';
                    });
                </script>";
            } else {
                // echo "Error: Unable to update configuration file.<br>";
                echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        document.getElementById('popup-message').textContent = 'Error: Unable to update configuration file.';
                        document.getElementById('popup').style.display = 'block';
                    });
                </script>";
            }
        } else {
            // echo "Error: Configuration file does not exist.<br>";
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('popup-message').textContent = 'Error: Configuration file does not exist.';
                    document.getElementById('popup').style.display = 'block';
                });
            </script>";
        }
    }


    // Handle uploads for both boards
    $board1Uploaded = handleFileUpload('fileToUploadBoard1', $targetDirBoard1, 'Board 1', '35');

    $board2Uploaded = handleFileUpload('fileToUploadBoard2', $targetDirBoard2, 'Board 2', '36');
}

$usbPaths = ['/run/media/sda1', '/run/media/sdb1'];
$swuFiles = []; // Array to hold found .swu files
$logMessages = []; // Array to hold log messages

// Function to scan the directories for .swu files
function scanForSwuFiles($paths)
{
    global $logMessages;
    $files = [];
    $usbConnected = false;

    foreach ($paths as $path) {
        // Check if the path exists

        if (is_dir($path)) {
            $usbConnected = true; // At least one USB is connected
            // Open the directory
            if ($handle = opendir($path)) {
                // Loop through the directory
                while (false !== ($file = readdir($handle))) {
                    // Check for .swu files
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'swu') {
                        // Store full path of found .swu files
                        $files[] = htmlspecialchars($path . '/' . $file);
                    }
                }
                closedir($handle);
            } else {
                $logMessages[] = "Could not open the directory: $path";
            }
        } else {
            $logMessages[] = "The specified path does not exist: $path";
        }
    }

    // Log messages based on the results of the scan
    if (!$usbConnected) {
        $logMessages[] = "No USB devices connected.";
    } elseif (empty($files)) {
        $logMessages[] = "USB is connected, but no .swu files found.";
    } else {
        $logMessages[] = "SWU files found: " . implode(', ', $files);
    }

    return $files; // Return the array of found .swu files
}

// Function to validate USB devices
function USB_validation()
{
    $devicePath_1 = '/dev/sda';
    $devicePath_2 = '/dev/sdb';

    // Check if either device exists
    if (file_exists($devicePath_1) || file_exists($devicePath_2)) {
        return true;
    } else {
        return false; // USB device does not exist or is not accessible
    }
}

// Validate USB devices
if (USB_validation()) {
    // Scan for .swu files if USB devices are found
    $swuFiles = scanForSwuFiles($usbPaths);
} else {
    $logMessages[] = "Error: USB device does not exist or is not accessible.";
}

// Log messages to swu.txt
file_put_contents('swu.txt', implode("\n", $logMessages));

// Collect error messages to display
$errorMessages = array_filter($logMessages, function ($message) {
    return strpos($message, 'Error') !== false || strpos($message, 'No USB') !== false;
});

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $board1File = $_POST['board1'] ?? null;
    $board2File = $_POST['board2'] ?? null;

    // Validate selections
    if ($board1File && $board2File) {
        // Update the configuration.json file
        $configFilePath = '/etc/configuration.json';

        // Load existing configuration
        $config = file_exists($configFilePath) ? json_decode(file_get_contents($configFilePath), true) : [];

        // Update keys 35 and 36 with the full paths
        $config[35] = $board1File;
        $config[36] = $board2File;

        // Write the updated configuration back to the file
        if (file_put_contents($configFilePath, json_encode($config, JSON_PRETTY_PRINT))) {
            // Write value 5 to FIFO
            file_put_contents('/etc/request', '5');

            // echo "<div class='alert alert-success'>Configuration updated successfully.</div>";
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('popup-message').textContent = 'Configuration updated successfully.';
                    document.getElementById('popup').style.display = 'block';
                });
            </script>";
        } else {
            // echo "<div class='alert alert-danger'>Error updating configuration.</div>";
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('popup-message').textContent = 'Error updating configuration.';
                    document.getElementById('popup').style.display = 'block';
                });
            </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Stryker</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/wifi.css">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/jquery-3.7.1.min.js"></script>
    <style>
        body {
            background-color: #FEF8F8;
        }

        .big-radio {
            appearance: none;
            /* Remove default appearance */
            width: 30px;
            /* Increase size */
            height: 30px;
            /* Increase size */
            border-radius: 50%;
            /* Make it round */
            border: 3px solid #d7932b;
            /* Set border color */
            background-color: white;
            /* Default background color */
            position: relative;
            outline: none;
            /* Remove outline */
            transition: background-color 0.3s ease;
            /* Smooth transition */
        }

        .big-radio:checked {
            background-color: #d7932b;
            /* Change background color when checked */
        }

        .big-radio:checked::before {
            content: '';
            position: absolute;
            top: 7px;
            /* Position the inner circle */
            left: 7px;
            /* Position the inner circle */
            width: 15px;
            /* Set the size of the inner circle */
            height: 15px;
            /* Set the size of the inner circle */
            border-radius: 50%;
            /* Make the inner circle round */
            background-color: white;
            /* Inner circle color */
        }



        input[type="radio"] {
            margin-right: 10px;
            /* Add space between radio button and text */
            margin-top: 0;
            /* Remove top margin */
        }

        h2 {
            margin-right: 10px;
            /* Add space between h2 and radio button */
        }

        .btn_radio {
            display: flex;
            align-items: center;

        }

        .container {
            display: flex;
            justify-content: space-between;
            height: 80vh;
            margin: 20px;
            align-items: center;
            background-color: #FEF8F8;
            border: none;
            /* Optional: Space around the entire container */
        }

        .frame {
            flex: 1;
            margin: 10px;
            /* Space between frames */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            height: 400px;
        }

        .header {
            background-color: #17a2b8;
            color: black;
            padding: 20px;
            text-align: center;
        }

        .upload-btn {
            margin-top: 15px;
            /* Increased space above the button */
            color: black;
            background-color: #d7932b;
            border: none;
            /* Optional: remove default border */
            padding: 10px 20px;
            /* Optional: add padding for better appearance */
            border-radius: 5px;
            /* Optional: rounded corners */
            cursor: pointer;
            /* Change cursor to pointer */
            transition: background-color 0.3s;
            /* Smooth transition for background color */
        }

        .upload-btn:hover {
            background-color: #aa7b24;
            /* Change background color on hover */
            color: black;
            /* Change text color on hover */
        }

        /* Additional spacing for headings and inputs */


        .frame h4 {
            margin-top: 15px;
            /* Space above each subheading */
            margin-bottom: 5px;
            /* Space below each subheading */
        }

        .frame input[type="file"] {
            margin-bottom: 15px;
            /* Space below the file input fields */
        }

        .disabled {
            pointer-events: none;
        }

        .loader {
            display: none;
            width: 300px;
            --b: 8px;
            aspect-ratio: 1;
            border-radius: 50%;
            padding: 1px;
            background: conic-gradient(#0000 10%, #e1a80a) content-box;
            -webkit-mask:
                repeating-conic-gradient(#0000 0deg, #000 1deg 20deg, #0000 21deg 36deg),
                radial-gradient(farthest-side, #0000 calc(100% - var(--b) - 1px), #000 calc(100% - var(--b)));
            -webkit-mask-composite: destination-in;
            mask-composite: intersect;
            animation: l4 1s infinite steps(10);
            position: fixed;
            top: 40%;
            left: 40%;
            /* transform: translate(-50%,-50%); */
        }



        @keyframes l4 {
            to {
                transform: rotate(1turn)
            }
        }
    </style>
</head>

<body>
    <div class="bg-info fs-1 d-flex py-4 px-3 text-black align-items-center border-bottom border-4 border-dark header">
        <img src="assets/logo.png" alt="Logo" class="header-logo me-3" style="height: 60px;">
        <!-- Adjust height as needed -->
    </div>
    <div class="popup" id="popup">
        <div class="popup-content">
            <p id="popup-message">Configuration saved successfully!</p>
            <button id="close-popup">Close</button>
        </div>
    </div>
    <div class="d-flex section ">
        <div class="d-flex flex-column w-200 bg-dark section-left">
            <a href="index.php" class="text-decoration-none py-4 px-3 text-white">Home</a>
            <a href="#" class="text-decoration-none py-4 px-3 text-white dropdown-toggle">Network</a>
            <!-- Network Tabs -->
            <ul id="network-dropdown" class="dropdown" style="display: none;">
                <li><a href="wifi.php" class="text-decoration-none">WiFi</a></li>
                <li><a href="video.php" class="text-decoration-none">Video</a></li>
            </ul>
            <a href="logs.php" class="text-decoration-none py-4 px-3 text-white">Log</a>
            <a href="upgrade.php" class="text-decoration-none py-4 px-3 text-white active">Upgrade</a>
            <a href="system-info.php" class="text-decoration-none py-4 px-3 text-white">System Info</a>
            <a href="help.php" class="text-decoration-none py-4 px-3 text-white">Help</a>
            <a href="restart.php" class="text-decoration-none py-4 px-3 text-white">Restart</a>
        </div>
        <div class="container">
            <!-- Manual OTA using USB -->
            <div class="frame" id="manual-ota">

                <label class="btn_radio">
                    <input type="radio" name="ota" value="manual" class="big-radio" onclick="toggleFields('one')">

                    <h2>Manual</h2>

                </label>
                <div id="one">
                    <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
                        <div>
                            <h4>Select for Board 1:</h4>
                            <div class="form-group">
                                <select class="form-control" id="board1" name="board1">
                                    <?php if (!empty($swuFiles)): ?>
                                    <?php foreach ($swuFiles as $index => $file): ?>
                                    <option value="<?php echo $file; ?>" <?php echo $index===0 ? 'selected' : '' ; ?>>
                                        <?php echo basename($file); ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <option value="">No .swu files available</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <h4>Select for Board 2:</h4>
                            <select class="form-control" id="board2" name="board2">
                                <?php if (!empty($swuFiles)): ?>
                                <?php foreach ($swuFiles as $index => $file): ?>
                                <option value="<?php echo $file; ?>" <?php echo $index===0 ? 'selected' : '' ; ?>>
                                    <?php echo basename($file); ?>
                                </option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <option value="">No .swu files available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <button class="btn btn-primary mt-3 upload-btn" id="startUpdate">Start Update</button>
                    </form>
                </div>
            </div>
            <div class="frame mt-1" id="ota-settings">

                <label class="btn_radio">
                    <input type="radio" name="ota" value="ota" class="big-radio" onclick="toggleFields('two')">

                    <h2>OTA</h2>

                </label>
                <div id="two">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div>
                            <h4>Upload for Board 1:</h4>
                            <input type="file" name="fileToUploadBoard1" accept=".swu" required class="mt-1">
                            <button type="submit" name="uploadBoard1" class="btn upload-btn">Upload File for Board
                                1</button>
                        </div>
                    </form>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div>
                            <h4>Upload for Board 2:</h4>
                            <input type="file" name="fileToUploadBoard2" accept=".swu" required class="mt-1">
                            <button type="submit" name="uploadBoard2" class="btn upload-btn">Upload File for Board
                                2</button>
                        </div>
                    </form>
                    <button type="button" name="startUpdate" class="btn upload-btn mb-2" onclick="startUpdate()"
                        id="startupdate">Start Update</button>
                </div>
            </div>
        </div>

        <div class="loader" id="loader"></div>
        <script>
            async function startUpdate() {
                const response = await fetch('swupdate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const result = await response.json();
                document.getElementById("popup-message").textContent = result.message;
                document.getElementById("popup").style.display = "block";
                document.getElementById("popup-overlay").style.display = "block";
            }

            document.getElementById('startupdate').addEventListener('click', function () {
                // Disable pointer events on the body to prevent further interaction
                document.body.classList.add('disabled');

                document.querySelector('.loader').style.display = 'block'; // Show loader
            });

            document.getElementById('startUpdate').addEventListener('click', function () {
                // Disable pointer events on the body to prevent further interaction
                document.body.classList.add('disabled');

                document.querySelector('.loader').style.display = 'block'; // Show loader
            });


            // Close the popup when the user clicks the "Close" button
            document.getElementById("close-popup").onclick = function () {
                document.getElementById("popup").style.display = "none";

                document.getElementById("popup-overlay").style.display = "none";
                location.reload();
            };

            function toggleFields(id) {
                // Get the currently selected radio button value
                if (id === "one") {
                    const a = document.getElementById("one")
                    a.classList.remove("disableAllItem")
                    const b = document.getElementById("two")
                    b.classList.add("disableAllItem")
                } else {
                    const b = document.getElementById("two")
                    b.classList.remove("disableAllItem")
                    const a = document.getElementById("one")
                    a.classList.add("disableAllItem")
                }

            }

            document.addEventListener('DOMContentLoaded', function () {
                const a = document.getElementById("one")
                const b = document.getElementById("two")
                a.classList.add("disableAllItem")
                b.classList.add("disableAllItem")
            })
        </script>
        <style>
            .disableAllItem {
                pointer-events: none;
                opacity: 0.5;
            }
        </style>
</body>

</html>