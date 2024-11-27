<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $directories = ['/etc', '/mnt'];
    $swuFileFound = true;

    // Check each directory for .swu files
    foreach ($directories as $directory) {
        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'swu') {
                    $swuFileFound = true;
                    break 2; // Exit both loops if a .swu file is found
                }
            }
        }
    }

    // If a .swu file is found, write to the FIFO
    if ($swuFileFound) {
        $fifoPath = '/etc/request';
        $result = @file_put_contents($fifoPath, '5');

        if ($result !== false) {
            echo json_encode(['status' => 'success', 'message' => 'Files uploaded successfully. Update started.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unable to write to FIFO.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No .swu file found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
