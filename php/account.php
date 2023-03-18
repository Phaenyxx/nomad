<?php
session_start();
if (!isset($_SESSION['msg']))
  $_SESSION['msg'] = array();
$_SESSION['page'] = "./php/account.php"
    ?>
<h1> COMPTE </h1>