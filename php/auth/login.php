<?php
    session_start();
    $_SESSION['page'] = "./php/auth/login.php"
?>

<h2> Connexion / Enregistrement</h2>
<div class="container">
<div class="buttoncontainer">  
    <button id="logbutton" onclick="switch_form('login', './php/auth/login_form.html')">Se connecter</button>
    <button id="regbutton" onclick="switch_form('register', './php/auth/register_form.html')">S'enregistrer</button>
</div>
<div id="form_container"></div>
</div>