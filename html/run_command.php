<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['command'])) {
    $command = $_POST['command'];
    
    // List of allowed commands to run (whitelist approach)
    $allowed_commands = [
        'gst-launch-1.0',  // Allow only gst-launch commands
        'ls',              // Example command for testing
        'echo'             // Example command for testing
    ];

    // Sanitize input: Ensure the command is from the allowed list
    if (in_array($command, $allowed_commands)) {
        $output = [];
        $status = null;

        // Execute the command and capture output
        exec($command, $output, $status);

        if ($status === 0) {
            echo "Command executed successfully. Output:\n";
            echo implode("\n", $output);
        } else {
            echo "Error executing command. Exit status: $status";
        }
    } else {
        echo "Invalid command.";
    }
} else {
    echo "Invalid request.";
}
?>
