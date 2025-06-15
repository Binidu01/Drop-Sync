<?php
session_start();
include 'config.php';

$folder_name = ''; // Declare the variable here

if (isset($_POST['action']) && $_POST['action'] == 'create_folder') {
    $folder_name = $_POST['folder_name']; // Update this line
    createFolder($folder_name);
}
echo $folder_name; // Now you can access the variable here

function createFolder($folder_name) {
    $userid = $_SESSION['userid'];
    $folder_path = "storage/$userid/$folder_name";
    if (!file_exists($folder_path)) {
        mkdir($folder_path, 0777, true);
        echo "Folder created successfully";
        header('Location: storage.php'); // Redirect to storage.php

    } else {
        echo "Folder already exists";
    }
}
?>