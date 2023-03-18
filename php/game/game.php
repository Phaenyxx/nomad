<?php
if (!isset($_SESSION)) {
  session_start();
  $_SESSION['page'] = "./php/game/game.php";
  include_once('../../../config.php');
}

include('./char_info.php');
?>

<div class="container">
  <?= $_SESSION['character']['name'] ?>
</div>

<div id="map-outer-container">
  <div class="buttoncontainer">
    <a class="link linkbox" href="#" onclick="moveplayer('up')"> UP</a>
  </div>
  <div class="row">
    <div class="buttoncontainer">
      <a class="link linkbox" href="#" onclick="moveplayer('left')"> LEFT</a>

    </div>
    <div id="map-container">
      <?php
      include('./load_map.php');
      loadmap();
      ?>
    </div>
    <div class="buttoncontainer">
      <a class="link linkbox" href="#" onclick="moveplayer('right')"> RIGHT</a>
    </div>
  </div>
  <div class="buttoncontainer">
    <a class="link linkbox" href="#" onclick="moveplayer('down')"> DOWN</a>
  </div>
</div>
<div class="buttoncontainer">
  <a class="link linkbox" href="#" onclick="mapPopup()">Consulter la carte</a>
</div>