<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = '/mnt/'; // Change this to your desired directory

    // Create target directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Debugging: Print $_FILES for inspection
    echo '<pre>';
    print_r($_FILES);
    echo '</pre>';

    // Check if the file input exists
    if (!isset($_FILES['fileToUpload'])) {
        echo "No file uploaded or the input name is incorrect.";
        exit;
    }

    $fileToUpload = $_FILES['fileToUpload'];

    // Check for upload errors
    if ($fileToUpload['error'] !== UPLOAD_ERR_OK) {
        echo "Error during file upload: " . $fileToUpload['error'];
        exit;
    }

    // Validate file extension
    $allowedExtension = 'swu';
    $fileExtension = pathinfo($fileToUpload['name'], PATHINFO_EXTENSION);

    if (strtolower($fileExtension) !== $allowedExtension) {
        echo "Error: Invalid file type. Only .swu files are allowed.";
        exit;
    }

    // Check file size (e.g., limit to 600MB, adjust as needed)
    $maxFileSize = 600 * 1024 * 1024; // 600MB
    if ($fileToUpload['size'] > $maxFileSize) {
        echo "Error: File size exceeds the maximum limit of 600MB.";
        exit;
    }

    // Sanitize the filename
    $sanitizedFileName = preg_replace("/[^a-zA-Z0-9._-]/", "_", basename($fileToUpload['name']));

    // Move the uploaded file
    $targetFilePath = $targetDir . $sanitizedFileName;
    if (move_uploaded_file($fileToUpload['tmp_name'], $targetFilePath)) {
        echo "File uploaded successfully to $targetFilePath.";
    } else {
        echo "Failed to move the uploaded file.";
    }
}
?>
<html>
    <body>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" required>
            <input type="submit" value="Upload">
        </form>
    </body>
</html>
