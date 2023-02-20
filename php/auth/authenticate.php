<?php
session_start();
if (!isset($_SESSION)) {
    session_start();
}
include('../../config.php');
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            $_SESSION['page'] = './php/game/game.php';
            $_SESSION['message'] = "Connexion réussie !";
            header('Location: ../../index.php');
        } else {
            $_SESSION['message'] = "Nom d'utilisateur ou mot de passe incorrect !";
        $_SESSION['page'] = './php/auth/login.php'
        header('Location: ../../index.php');
        }
    } else {
        $_SESSION['message'] = "Nom d'utilisateur ou mot de passe incorrect !";
        $_SESSION['page'] = './php/auth/login.php'
        header('Location: ../../index.php');
    }
	$stmt->close();
}
?>