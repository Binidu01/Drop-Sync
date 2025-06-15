<?php
if (isset($_POST['submit'])) {
    $folder_path = $_POST['folder_path'];
    $new_folder_name = $_POST['new_folder_name'];

    $folder_info = pathinfo($folder_path);
    $new_folder_path = $folder_info['dirname'] . '/' . $new_folder_name;

    if (rename($folder_path, $new_folder_path)) {
        echo "Folder renamed successfully";
        header('Location: storage.php');
    } else {
        echo "Error renaming folder";
    }
}
?>