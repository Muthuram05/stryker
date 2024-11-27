<?php
function getEth0MacAddress() {
    $filePath = '/sys/class/net/eth0/address';

    // Check if the file exists
    if (file_exists($filePath)) {
        // Read the content of the file
        return trim(file_get_contents($filePath));
    } else {
        return 'No network';
    }
}

// Output the MAC address
echo getEth0MacAddress();
