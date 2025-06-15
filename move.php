<?php
session_start();
include 'config.php';

$file_path = '';

if (isset($_POST['selected_folder']) && isset($_POST['file_path'])) {
    $selected_folder = $_POST['selected_folder'];
    $file_path = $_POST['file_path'];
    moveFile($selected_folder, $file_path, $conn);
}


function moveFile($selected_folder, $file_path, $conn) {
    $userid = $_SESSION['userid'];
    $selected_folder = rtrim($selected_folder, '/'); // Remove trailing directory separator
    if (!empty($selected_folder)) {
        $destination_path = "storage/$userid/$selected_folder/" . basename($file_path);
    } else {
        $destination_path = "storage/$userid/" . basename($file_path);
    }
    if (copy($file_path, $destination_path)) {
        unlink($file_path);  
        echo "File moved successfully";
        // Update the storage table with the new file path
        $query = "UPDATE storage SET file_path = '$destination_path' WHERE file_path = '$file_path'";
        if (mysqli_query($conn, $query)) {
            header('Location: storage.php'); // Redirect to storage.php
        } else {
            echo "Error updating database: " . mysqli_error($conn);

        }
    } else {
    } header('Location: storage.php'); // Redirect to storage.php
}