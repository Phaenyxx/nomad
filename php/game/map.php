<?php
if (!isset($_SESSION)) {
  session_start();
  $_SESSION['page'] = "./php/game/game.php";
  include_once('../../../config.php');
}

include('./char_info.php');
include('./load_map.php');
loadmap(20, "minimap");
?>