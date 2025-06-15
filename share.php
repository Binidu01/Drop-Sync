<?php
session_start();
include 'config.php';

$userid = $_SESSION['userid'];

if (isset($_REQUEST['file'])) {
    $file_name = $_REQUEST['file'];
    $file_path = 'storage/' . $userid . '/' . $file_name;
    echo $file_path;
} else {
    echo 'Error: File name not provided.';
}
?>