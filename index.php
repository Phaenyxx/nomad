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
    <script type="text/javascript">
        loadmain("<?=$_SESSION['page'] ?>");
    </script>
</body>
</html>