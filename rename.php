<?php
include 'config.php'; // Make sure to include the database connection code

function renameFile($filePath, $newFileName) {
    global $conn; // Add the global keyword to access the $conn variable
    $file_info = pathinfo($filePath);
    $extension = $file_info['extension'];
    $newFilePath = $file_info['dirname'] . '/' . $newFileName . '.' . $extension;

    if (rename($filePath, $newFilePath)) {
        $query = "UPDATE storage SET file_name = '$newFileName', file_path = '$newFilePath' WHERE file_path = '$filePath'";
        $conn->query($query); // Now $conn should be defined
        echo "File renamed successfully";
        header('Location: storage.php'); // Redirect to storage.php
    } else {
        echo "Error renaming file";
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'rename') {
    $filePath = $_POST['file_path']; // Correct the key name
    $newFileName = $_POST['new_file_name']; // Correct the key name
    renameFile($filePath, $newFileName);
}
?>