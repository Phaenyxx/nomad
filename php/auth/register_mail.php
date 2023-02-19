<?php
include('../../config.php');
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
		$_SESSION['message'] = "Ce nom d'utilisateur existe déjà, veuillez en choisir un autre !";
	} else {
		if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$uniqid = uniqid();
			$stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
			$stmt->execute();
			$from    = 'noreply@yourdomain.com';
			$subject = 'Account Activation Required';
			$headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
			$activate_link = 'http://yourdomain.com/phplogin/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
			$message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
			mail($_POST['email'], $subject, $message, $headers);
			$_SESSION['message'] = "Veuillez vérifier vos mails pour activer votre compte !";
			header('Location: ../../index.php');
		} else {
			echo 'Could not prepare statement!';
		}
	}
	$stmt->close();
} else {
	echo 'Could not prepare statement!';
}
$con->close();
?>