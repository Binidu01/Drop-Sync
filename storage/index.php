<?php
$url = $_SERVER['REQUEST_URI'];
if (strpos($url, '/drop%20sync/storage') !== false) {
  $new_url = str_replace('/storage', '', $url);
  header('Location: ' . $new_url);
  exit;
}
?>