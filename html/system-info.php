<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stryker</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/jquery-3.7.1.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
    <script src="assets/popper.min.js"></script>
    <style>
        body {
            background-color: #FEF8F8;
        }

        .disable {
            display: none;
        }

        .card-header {
            background-color: #9c9c9c !important;
        }
    </style>
</head>

<bod>

    <div class="bg-info fs-1 d-flex py-4 px-3 text-black align-items-center border-bottom border-4 border-dark header">
        <img src="assets/logo.png" alt="Logo" class="header-logo me-3" style="height: 60px;">
        <!-- Adjust height as needed -->
    </div>

    <div class="d-flex section">
        <div class="d-flex flex-column w-200 bg-dark section-left">
            <a href="index.php" class="text-decoration-none py-4 px-3 text-white">Home</a>
            <a href="#" class="text-decoration-none py-4 px-3 text-white dropdown-toggle">Network</a>
            <ul id="network-dropdown" class="dropdown" style="display: none;">
                <li><a href="wifi.php" class="text-decoration-none">WiFi</a></li>
                <li><a href="video.php" class="text-decoration-none">Video</a></li>
            </ul>
            <a href="logs.php" class="text-decoration-none py-4 px-3 text-white">Log</a>
            <a href="upgrade.php" class="text-decoration-none py-4 px-3 text-white">Upgrade</a>
            <a href="system-info.php" class="text-decoration-none py-4 px-3 text-white active">System Info</a>
            <a href="help.php" class="text-decoration-none py-4 px-3 text-white">Help</a>
            <a href="restart.php" class="text-decoration-none py-4 px-3 text-white">Restart</a>
        </div>

        <div class="container py-1">
            <h2>System Information</h2>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">System Parameters</h5>
                </div>
                <div class="card-body">
                    <p><strong>Manufacturing Date:</strong> <span id="manufacturing-date"></span></p>
                    <p><strong>Serial Number:</strong> <span id="serial-number"></span></p>
                    <p><strong>Software Versions:</strong> <span id="software-versions"></span></p>
                    <p><strong>PCBA Number:</strong> <span id="pcba-number"></span></p>
                    <p><strong><span>MAC Address eth1: </span></strong><span id="mac0">please wait...</span></p>
                    <p><strong><span>MAC Address eth2: </span></strong><span id="mac1">please wait...</span></p>
                    <p><strong><span>MAC Address eth3: </span></strong><span id="mac2"></span></p>
                    <p><strong><span>MAC Address eth4: </span></strong><span id="mac3"></span></p>

                    <p><strong>Memory Utilization:</strong> <span id="memory-utilization"><?php echo $currentUtilization; ?></span></p>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex">
                    <h5 class="card-title">Monitor Performance Parameters</h5>

                </div>
                <div class="card-body">
                    <div id="performanceParameters" style="display: none;">
                        <p><strong>Frame Drop:</strong> Average 2 frames</p>
                        <p><strong>Latency:</strong> 60 ms</p>
                        <p><strong>WiFi Bandwidth:</strong> <span id="bandwidth"></span></p>
                        <p><strong>Internet Speed:</strong> <span id="speed">Calculating...</span></p>
                    </div>

                    <div class="mb-3" id="setDisable">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="text" id="password" class="form-control" placeholder="Enter password" />
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const togglePasswordButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const performanceParameters = document.getElementById('performanceParameters');
        const setDisable = document.getElementById('setDisable');

        togglePasswordButton.addEventListener('click', () => {
            const enteredPassword = passwordInput.value;
            if (enteredPassword === 'stryker') {
                performanceParameters.style.display = 'block';

                setDisable.classList.add("disable")
            } else {
                alert('Incorrect password. Please try again.');
                passwordInput.value = '';
            }
        });
        // Function to fetch the bandwidth value from the file and update the HTML
        fetch('data.txt')
            .then(response => response.text())
            .then(data => {
                // Split the file content by lines and remove empty lines
                const lines = data.split('\n').filter(line => line.trim() !== '');

                // Get the bandwidth from the first line
                const bandwidthValue = lines[0].trim();
                document.getElementById('bandwidth').textContent = bandwidthValue;

                // Get the MAC addresses from the second and third lines
                const macAddressEth3 = lines[1].trim();
                const macAddressEth4 = lines[2].trim();

                // Update the content of the spans with the MAC addresses
                document.getElementById('mac2').textContent = macAddressEth3;
                document.getElementById('mac3').textContent = macAddressEth4;
            })
            .catch(error => console.error('No network:', error));

        fetch('info.txt')
            .then(response => response.text())
            .then(data => {
                // Split the file content by new lines
                const lines = data.split('\n');

                // Populate the HTML with the extracted data
                document.getElementById('manufacturing-date').textContent = lines[0].trim();
                document.getElementById('serial-number').textContent = lines[1].trim();
                document.getElementById('software-versions').textContent = lines[2].trim();
                document.getElementById('pcba-number').textContent = lines[3].trim();
            })
            .catch(error => {
                console.error('Error fetching the file:', error);
            });
    </script>

    </body>

</html>