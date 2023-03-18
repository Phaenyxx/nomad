<?php
if (!isset($_SESSION)) {
  session_start();
  $_SESSION['msg'] = array();
}
if (!isset($_SESSION['character'])) {

  include_once('../../../config.php');
  $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
  if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
  }
  $stmt = $con->prepare('SELECT * FROM persos WHERE username=? AND character_status="alive" LIMIT 1');
  $stmt->bind_param('s', $_SESSION['name']);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $character = $result->fetch_assoc();
    $_SESSION['character'] = $character;
  } else {
    $stmt = $con->prepare('SELECT * FROM persos WHERE username=? AND character_status="dead" LIMIT 1');
    $stmt->bind_param('s', $_SESSION['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      header("Location: ./new_party.php");
    } else {
      array_push($_SESSION['msg'], "Vous n'avez pas de personnage, veuillez en créer un");
      header("Location: ./char_crea.php");
    }
    exit();
  }
  mysqli_close($con);
}
?>