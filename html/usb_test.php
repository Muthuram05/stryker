<?php
// Array of paths to check for .swu files
$usbPaths = ['/run/media/sda1', '/run/media/sdb1'];
$swuFiles = []; // Array to hold found .swu files
$logMessages = []; // Array to hold log messages

// Function to scan the directories for .swu files
function scanForSwuFiles($paths) {
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
function USB_validation() {
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

            echo "<div class='alert alert-success'>Configuration updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating configuration.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Please select .swu files for both boards.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USB SWU File Selection</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        window.onload = function() {
            <?php if (!empty($errorMessages)): ?>
                alert("<?php echo implode('\\n', $errorMessages); ?>");
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Select SWU Files for Update</h1>
        <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
            <div>
                <h4>Select for Board 1:</h4>
                <div class="form-group">
                    <select class="form-control" id="board1" name="board1">
                        <?php if (!empty($swuFiles)): ?>
                            <?php foreach ($swuFiles as $index => $file): ?>
                                <option value="<?php echo $file; ?>" <?php echo $index === 0 ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $file; ?>" <?php echo $index === 0 ? 'selected' : ''; ?>>
                                <?php echo basename($file); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No .swu files available</option>
                    <?php endif; ?>
                </select>
            </div>
            <button class="btn btn-primary mt-3 upload-btn">Start Update</button>
        </form>
    </div>

    <!-- Optional JavaScript and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
