<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Get the raw POST data
$data = file_get_contents('php://input');

// Decode the JSON data into a PHP associative array
$networkConfig = json_decode($data, true);

// Function to validate if an IP address is in a given range
function validateIPRange($ipAddress, $range)
{
    list($subnet, $maskBits) = explode('/', $range);
    $ipLong = ip2long($ipAddress);
    $subnetLong = ip2long($subnet);
    $mask = -1 << (32 - $maskBits);
    $rangeStart = $subnetLong & $mask;
    $rangeEnd = $rangeStart + (~$mask);
    return ($ipLong >= $rangeStart && $ipLong <= $rangeEnd);
}

// Validate if all the necessary fields are present
if (isset(
    $networkConfig['ipAddressB1'],
    $networkConfig['portB1'],
    $networkConfig['ipAddressB2'],
    $networkConfig['portB2'],
    $networkConfig['24'],  // Loopout for Board 1
    $networkConfig['25'],  // Loopout for Board 2
    $networkConfig['26'],  // WiFi value for Board 1
    $networkConfig['27'],  // WiFi value for Board 2
    $networkConfig['17'],
    $networkConfig['18']
)) {

    // Validate IP address format
    if (
        filter_var($networkConfig['ipAddressB1'], FILTER_VALIDATE_IP) === false ||
        filter_var($networkConfig['ipAddressB2'], FILTER_VALIDATE_IP) === false
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid IP address format.']);
        exit;
    }

    // Validate IP address range (e.g., for private network 192.168.0.0/16)
    if (
        !validateIPRange($networkConfig['ipAddressB1'], '192.168.0.0/16') ||
        !validateIPRange($networkConfig['ipAddressB2'], '192.168.0.0/16')
    ) {
        echo json_encode(['status' => 'error', 'message' => 'IP address out of allowed range.']);
        exit;
    }

    // Validate port numbers
    if (
        !is_numeric($networkConfig['portB1']) || !is_numeric($networkConfig['portB2']) ||
        $networkConfig['portB1'] < 1000 || $networkConfig['portB1'] > 65535 ||
        $networkConfig['portB2'] < 1000 || $networkConfig['portB2'] > 65535
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Ports must be between 1000 and 65535.']);
        exit;
    }

    if ($networkConfig['portB1'] == $networkConfig['portB2']) {
        echo json_encode(['status' => 'error', 'message' => 'Ports must be different. Please enter an alternate port address.']);
        exit;
    }

    // Define the path to your JSON file
    $configFilePath = '/etc/configuration.json';  // Update this path as per your setup

    // Load the existing JSON file
    if (file_exists($configFilePath)) {
        $currentConfig = json_decode(file_get_contents($configFilePath), true);
    
        // Check if decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON format in configuration file.']);
            exit;
        }

        // Update the specific values in the JSON structure
        $currentConfig['19'] = $networkConfig['ipAddressB1'];
        $currentConfig['20'] = $networkConfig['portB1'];
        $currentConfig['22'] = $networkConfig['ipAddressB2'];
        $currentConfig['23'] = $networkConfig['portB2'];
        $currentConfig['24'] = $networkConfig['24'] == "1" ? "1" : "0";  // Loopout for Board 1
        $currentConfig['25'] = $networkConfig['25'] == "1" ? "1" : "0";  // Loopout for Board 2
        $currentConfig['26'] = $networkConfig['26'] != "0" ? $networkConfig['26'] : "0"; // WiFi for Board 1
        $currentConfig['27'] = $networkConfig['27'] != "0" ? $networkConfig['27'] : "0"; // WiFi for Board 2
        $currentConfig['17'] = $networkConfig['17'];
        $currentConfig['18'] = $networkConfig['18'];
        $currentConfig['21'] = ($networkConfig['24'] == "0" && $networkConfig['25'] == "0") ? "0" : "1";  // Set '21' based on loopout checks

        // Write the updated configuration back to the JSON file
        if (file_put_contents($configFilePath, json_encode($currentConfig, JSON_PRETTY_PRINT))) {
            echo json_encode(['status' => 'success', 'message' => 'Configuration saved successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save configuration.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Configuration file not found.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields in the network configuration.']);
    exit;
}
?>
