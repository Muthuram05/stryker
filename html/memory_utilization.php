<?php
function getMemoryUtilization()
{
    // Execute the free command and get its output
    $output = shell_exec('free -m');
    $lines = explode("\n", $output);
    
    // Extract the memory usage information
    $memoryInfo = preg_split('/\s+/', trim($lines[1])); // Ensure there's no leading/trailing whitespace

    // Total and used memory in MB
    $totalMemory = $memoryInfo[1];
    $usedMemory = $memoryInfo[2];

    // Calculate memory utilization percentage
    $memoryUtilization = ($usedMemory / $totalMemory) * 100;

    // Return the memory utilization rounded to 2 decimal places
    return round($memoryUtilization, 2);
}

// Output the current memory utilization
echo getMemoryUtilization();

