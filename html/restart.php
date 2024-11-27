<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stryker</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/restart.css">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/jquery-3.7.1.min.js"></script>

    <style>
        body {
            background-color: #FEF8F8;
        }

        <?php
        // Handle the POST request when the "Restart" button is clicked
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Function to send a command via FIFO
            function fifow($sendval)
            {
                $pipePath = '/etc/request'; // Path to FIFO

                // Check if the FIFO exists
                if (!file_exists($pipePath)) {
                    die('Error: FIFO does not exist.');
                }

                // Open the FIFO for writing
                $pipe = fopen($pipePath, 'w');
                if (!$pipe) {
                    die('Error: Failed to open the FIFO for writing.');
                }

                // Write the command into the FIFO
                $bytesWritten = fwrite($pipe, $sendval);
                if ($bytesWritten === false) {
                    die('Error: Failed to write to the FIFO.');
                } elseif ($bytesWritten === 0) {
                    die('Warning: No data was written to the FIFO.');
                }

                // Close the FIFO
                fclose($pipe);
            }

            // Pass the value 4 to the FIFO for reboot
            fifow(4); // Send the value 4 instead of 'reboot'

            // Redirect to homepage or notify the user of success
            header('Location: index.php'); // Redirect to homepage after the request
            exit();
        }
        ?>

        /* Add this style to disable pointer events */
        .disabled {
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="bg-info fs-1 d-flex py-4 px-3 text-black align-items-center border-bottom border-4 border-dark header">
        <img src="assets/logo.png" alt="Logo" class="header-logo me-3" style="height: 60px;">
    </div>

    <div class="d-flex section">
        <div class="d-flex flex-column w-200 bg-dark section-left">
            <a href="index.php" class="text-decoration-none py-4 px-3 text-white">Home</a>
            <a href="#" class="text-decoration-none py-4 px-3 text-white dropdown-toggle" data-content="network">Network</a>
            <ul id="network-dropdown" class="dropdown" style="display: none;">
                <li><a href="wifi.php" class="text-decoration-none">WiFi</a></li>
                <li><a href="video.php" class="text-decoration-none">Video</a></li>
            </ul>
            <a href="logs.php" class="text-decoration-none py-4 px-3 text-white">Log</a>
            <a href="upgrade.php" class="text-decoration-none py-4 px-3 text-white">Upgrade</a>
            <a href="system-info.php" class="text-decoration-none py-4 px-3 text-white">System Info</a>
            <a href="help.php" class="text-decoration-none py-4 px-3 text-white">Help</a>
            <a href="restart.php" class="text-decoration-none py-4 px-3 text-white active">Restart</a>
        </div>

        <div class="p-4">
            <h2>Click here to restart</h2>
            <form id="restartForm" action="" method="POST">
                <button type="submit" class="btn button">Restart</button>
            </form>
        </div>
    </div>

    <div class="loader" id="loader"></div>

    <script>
        document.getElementById('restartForm').addEventListener('submit', function() {
            // Disable pointer events on the body to prevent further interaction
            document.body.classList.add('disabled');

           document.querySelector('.loader').style.display = 'block'; // Show loader
            // Hide loader when the operation is complete
        //    document.querySelector('.loader').style.display = 'none';

        });
    </script>
</body>

</html>