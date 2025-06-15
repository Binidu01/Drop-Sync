<?php
include 'config.php'; // Make sure to include the database connection code

if (isset($_GET['file_path'])) {
    $filePath = $_GET['file_path'];
    $file_info = pathinfo($filePath);


    if (file_exists($filePath)) {
        unlink($filePath);
        $query = "DELETE FROM storage WHERE file_path = '$filePath'";
        $conn->query($query);
        echo "File deleted successfully";
        header('Location: storage.php'); // Redirect to storage.php
    } else {
        echo "Error: File not found";
        echo $filePath;
    }
} else {
    echo "Error: File path not provided";
}
?>