<?php
$folder_path = $_GET['folder_path'];

if (rmdir($folder_path)) {
    echo "Folder deleted successfully";
    header('Location: storage.php');
} else {
    echo "Error deleting folder";
}
?>