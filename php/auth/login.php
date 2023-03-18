<?php
session_start();
if (!isset($_SESSION['msg']))
  $_SESSION['msg'] = array();
$_SESSION['page'] = "./php/auth/login.php"
    ?>

<h2> Connexion / Enregistrement</h2>
<div class="container">
    <div class="buttoncontainer">
        <button id="logbutton" class="linkbox" onclick="switch_form('login', './php/auth/login_form.html')">Se
            connecter</button>
        <button id="regbutton" class="linkbox"
            onclick="switch_form('register', './php/auth/register_form.html')">S'enregistrer</button>
    </div>
    <div id="form_container"></div>
</div>