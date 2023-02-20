<?php
session_start();
include('../../../config.php');
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	exit('Please complete the registration form!');
}
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}
if (strlen($_POST['password']) < 5) {
	exit('Password must be between at least 5 characters long!');
}
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$_SESSION['message'] = "Username exists, please choose another!";
		header('Location: ../../index.php');
	} else {
		if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
			$stmt->execute();
			$_SESSION['message'] = "Votre compte a été créé avec succés, vous pouvez vous connecter";
			header('Location: ../../index.php');
		} else {
			$_SESSION['message'] = "Could not prepare statement!";
			header('Location: ../../index.php');
		}
	}
	$stmt->close();
} else {
	$_SESSION['message'] = "Could not prepare statement!";
	header('Location: ../../index.php');
}
$con->close();
?>