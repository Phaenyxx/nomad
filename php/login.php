<?php
    session_start();
    $_SESSION['page'] = "./php/login.php"
?>

<h2> Connexion / Enregistrement</h2>
<div class="container">
<div class="buttoncontainer">  
    <button id="logbutton" onclick="switch_form('login', './auth/login_form.html')">Se connecter</button>
    <button id="regbutton" onclick="switch_form('register', './auth/register_form.html')">S'enregistrer</button>
</div>
<div id="form_container"></div>
</div>