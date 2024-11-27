<?php
// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check if the action is valid
if (isset($data['action']) && ($data['action'] == 'enable' || $data['action'] == 'disable')) {
    $id = $data['id'] ?? null; // Ensure ID is set
    $wifiName = $data['wifiName'] ?? '0'; // Default to '0' if not set
    $wifiPassword = $data['wifiPassword'] ?? '0'; // Default to '0' if not set

    // Load current configuration
    $configFile = '/etc/configuration.json';

    // Check if the configuration file exists
    if (!file_exists($configFile)) {
        echo json_encode(['status' => 'success', 'message' => 'Configuration update sucessfully']);
        exit;
    }

    $config = json_decode(file_get_contents($configFile), true);

    // Validate if the configuration was loaded successfully
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to read configuration file']);
        exit;
    }

    // Update configuration based on ID
    switch ($id) {
        case '1':
            $config['6'] = $wifiName;
            $config['7'] = $wifiPassword;
            $config['5'] = $data['action'] == 'enable' ? '1' : '0';
            break;
        case '2': // Updated to handle WiFi 1B 2.4GHz
            $config['9'] = $wifiName; // Store WiFi name in key 9
            $config['10'] = $wifiPassword; // Store WiFi password in key 10
            $config['8'] = $data['action'] == 'enable' ? '1' : '0'; // Set enable/disable status
            break;
        case '3':
            $config['12'] = $wifiName; // Example for WiFi 2A
            $config['13'] = $wifiPassword;
            $config['11'] = $data['action'] == 'enable' ? '1' : '0';
            break;
        case '4': // This is assumed for WiFi 2B
            $config['15'] = $wifiName;
            $config['16'] = $wifiPassword;
            $config['14'] = $data['action'] == 'enable' ? '1' : '0';
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            exit;
    }

    // Save updated configuration
    if (file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT)) === false) {
        echo json_encode(['status' => 'error', 'message' => 'Could not write to configuration file']);
        exit;
    }
    else {
        echo json_encode(['status' => 'success', 'message' => 'configuration saved sucessfully']);
    }
    // Respond back
    // echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
