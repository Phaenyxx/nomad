<?php
session_start();


include('../../../config.php');
if (isset($_SESSION['name'])) {
    $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    $stmt = $con->prepare('SELECT COUNT(*) FROM persos WHERE username=?');
    $stmt->bind_param('s', $_SESSION['name']);
    $stmt->execute();
    $stmt->bind_result($num_characters);
    $stmt->fetch();
    mysqli_close($con);
    if ($num_characters >= 3) {
        $_SESSION['message'] = "Vous avez atteint la limite de personnages";
        $_SESSION['page'] = './php/game/game.php';
        header('Location: ../../index.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['name']) || !preg_match('/^[a-z\' -]+\z/i', $_POST['name'])) {
        $_SESSION['message'] = "Pseudo invalide : Veuillez n'utilisez que des lettres, espaces, apostrophes et tirets";
        $_SESSION['page'] = './php/game/game.php';
    } else {
        $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
        if (mysqli_connect_errno()) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
        if ($stmt = $con->prepare('INSERT INTO persos (username, name, position_x, position_y, character_status, health, hydrated, satiated, job_item_1, job_item_2) VALUES (?, ?, 0, 0, "alive", 20, 0, 0, 1, null)')) {
            $stmt->bind_param('ss', $_SESSION['name'], $_POST['name']);
            print_r($con->error);

            $stmt->execute();

            print_r($con->error);


            $stmt->close();
            $_SESSION['message'] = "Personnage créé et incarné !";
            unset($_SESSION['character']);
            $_SESSION['page'] = './php/game/game.php';
        }

    }
}
// header('Location: ../../index.php');
?>