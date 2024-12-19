<?php
session_start();
if (!isset($_SESSION['msg']))
  $_SESSION['msg'] = array();
$_SESSION['page'] = "./php/game/game.php";

include_once('../../../config.php');
include_once('./char_info.php');
?>


<div id="game-container">
  <div id="map-outer-container">
    <div class="buttoncontainer" up>
      <a class="link linkbox" href="#" onclick="moveplayer('up')">⌃</a>
    </div>
    <div class="buttoncontainer" left>
      <a class="link linkbox" href="#" onclick="moveplayer('left')"><</a>

    </div>
    <div id="map-container">
      <?php
      include_once('./load_map.php');
      loadmap();
      ?>
    </div>
    <div class="buttoncontainer" right>
      <a class="link linkbox" href="#" onclick="moveplayer('right')">></a>
    </div>
    <div class="buttoncontainer" down>
      <a class="link linkbox" href="#" onclick="moveplayer('down')">⌄</a>
    </div>
  </div>

  <div id="chat-outer-container" class="outer-container">
    CHAT
  </div>
  <div id="cell-outer-container" class="outer-container">
    CELL INFO
  </div>
  <div id="player-outer-container" class="outer-container">
    player
  </div>
  <div id="actions-outer-container" class="outer-container">
    ACTIONS
    <div class="buttoncontainer" minimap>
      <a class="link linkbox" href="#" onclick="mapPopup()">Consulter la carte</a>
    </div>
  </div>
</div>