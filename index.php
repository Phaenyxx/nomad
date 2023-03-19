<!doctype html>
<html lang="FR-fr">
<?php
session_start();
if (!isset($_SESSION['msg']))
    $_SESSION['msg'] = array();
if (!isset($_SESSION['page']))
    $_SESSION['page'] = './html/accueil.html';

include 'html/head.html';
?>

<head>
    <title>Nomad - Accueil</title>
</head>

<body>
    <?php
    include 'php/header.php';
    ?>
    <div id="content"></div>
    <div id="message-box" onclick="hideMessageBox()"></div>
    <script type="text/javascript">
        loadmain("<?= $_SESSION['page'] ?>");
    </script>
    <?php
    include 'html/footer.html';
    ?>
</body>

</html>