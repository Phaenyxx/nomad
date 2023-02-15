<!doctype html>
<html lang="FR-fr">
<?php 
include 'html/head.html';
?>
<head>
<title>Nomad - Accueil</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['page']))
    $_SESSION['page'] = './html/accueil.html';
    
    include 'php/header.php';
    ?>
    <div id="content"></div>
    <div id="message-box" onclick="$('#message-box').hide()"></div>
    <script type="text/javascript">
        loadmain("<?=$_SESSION['page'] ?>");
    </script>
    <?php
    if (isset($_SESSION['message'])):?>
        <script type="text/javascript">
            print_message("<?=$_SESSION['message'] ?>");
        </script>
    <?php
    $_SESSION['message'] = NULL;
    endif; ?>
</body>
</html>