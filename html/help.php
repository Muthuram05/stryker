<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stryker</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
    <script src="assets/logo.png"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/jquery-3.7.1.min.js"></script>
    <style>
        body {
            background-color: #FEF8F8;
        }
    </style>
</head>

<body>
    <div class="bg-info fs-1 d-flex py-4 px-3 text-black align-items-center border-bottom border-4 border-dark header">
        <img src="assets/logo.png" alt="Logo" class="header-logo me-3" style="height: 60px;"> <!-- Adjust height as needed -->
    </div>

    <div class="d-flex section">
        <div class="d-flex flex-column w-200 bg-dark section-left">
            <a href="index.php" class="text-decoration-none py-4 px-3 text-white" data-content="">Home</a>
            <a href="#" class="text-decoration-none py-4 px-3 text-white dropdown-toggle" data-content="network">Network</a>

            <!-- Dropdown for Network Tabs -->
            <ul id="network-dropdown" class="dropdown" style="display: none;">
                <li><a href="wifi.php" class="text-decoration-none" data-content="wifi">WiFi</a></li>
                <li><a href="video.php" class="text-decoration-none" data-content="video">Video</a></li>

            </ul>

            <a href="logs.php" class="text-decoration-none py-4 px-3 text-white" data-content="logs">Log</a>
            <a href="upgrade.php" class="text-decoration-none py-4 px-3 text-white" data-content="upgrade">Upgrade</a>
            <a href="system-info.php" class="text-decoration-none py-4 px-3 text-white" data-content="system-info">System Info</a>
            <a href="help.php" class="text-decoration-none py-4 px-3 active text-white" data-content="help">Help</a>
            <a href="restart.php" class="text-decoration-none py-4 px-3 text-white" data-content="restart">Restart</a>
        </div>
        <h2>help</h2>
    </div>
    
</body>

</html>