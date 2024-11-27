<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the path to the configuration file
$configFile = '/etc/configuration.json'; // Update the path as needed

// Function to read the configuration file
function readConfig($file)
{
    if (!file_exists($file)) {
        http_response_code(404);
        echo json_encode(['message' => 'Configuration file not found.']);
        exit;
    }
    $json = file_get_contents($file);
    if ($json === false) {
        http_response_code(500);
        echo json_encode(['message' => 'Error reading configuration file.']);
        exit;
    }
    return json_decode($json, true);
}

// Function to write to the configuration file
function writeConfig($file, $data)
{
    if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) === false) {
        http_response_code(500);
        echo json_encode(['message' => 'Error writing to configuration file.']);
        exit;
    }
}

// Handle GET request to fetch current settings
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $config = readConfig($configFile);
    echo json_encode($config);
    exit;
}

// Handle POST request to enable or disable network
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (isset($inputData['wifiKey'], $inputData['ssid'], $inputData['password'])) {
        $config = readConfig($configFile);

        // Always set main WiFi config to enabled
        // $config['2'] = '1'; 

        // Resetting only the selected WiFi key
        switch ($inputData['wifiKey']) {
            case 'Disable':
                // If "Disable" is selected, disable both config keys 1 and 2
                $config['1'] = '0'; // Disable key 1
                $config['2'] = '0'; // Disable main WiFi config

                break;
            case '5': // WiFi 1A
                $config['5'] = '0'; // Enable WiFi 1A
                $config['2'] = '1';
                $config['1'] = '1';
                break;
            case '8': // WiFi 1B
                $config['8'] = '0';
                $config['2'] = '2';
                $config['1'] = '1'; // Enable WiFi 1B
                break;
            case '11': // WiFi 2A
                $config['11'] = '0'; // Enable WiFi 2A
                $config['2'] = '3';
                $config['1'] = '1';
                break;
            case '14': // WiFi 2B
                $config['14'] = '0'; // Enable WiFi 2B
                $config['2'] = '4';
                $config['1'] = '1';
                break;
            default:
                // Handle unexpected keys if necessary
                break;
        }

        // Set SSID and Password
        $config['3'] = strval($inputData['ssid']); // Ensure SSID is a string
        $config['4'] = strval($inputData['password']); // Ensure password is a string

        // Write the updated configuration to the file
        writeConfig($configFile, $config);

        http_response_code(200);
        echo json_encode(['message' => 'Configuration updated successfully!']);
    } else {
        echo json_encode(['message' => 'Invalid input: WiFi key, SSID, and Password are required.']);
    }
}
