<?php
session_start();
$messages = isset($_SESSION['msg']) ? $_SESSION['msg'] : array();
foreach ($messages as $message) {
  echo "<div id=\"message\">$message</div>";
}
unset($_SESSION['msg']);
?>