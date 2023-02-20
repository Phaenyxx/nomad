<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset ($_POST['submit']) && $_POST['submit']=='Poster') {
    if (!isset($_POST['auteur']) || !isset($_POST['message']) || !isset($_GET['id_sujet'])) {
        $erreur = 'Les variables nécessaires au script ne sont pas définies.';
    }
    else {
        if (empty($_POST['auteur']) || empty($_POST['message']) || empty($_GET['id_sujet'])) {
            $erreur = 'Au moins un des champs est vide.';
        }
        else {
            include('../../../config.php');
            $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
            if ( mysqli_connect_errno() ) {
                exit('Failed to connect to MySQL: ' . mysqli_connect_error());
            }
            $date = date("Y-m-d H:i:s");
            if ($stmt = $con->prepare('INSERT INTO forum_reponses VALUES("", ? , ? , "'.$date.'" , ?)')) {
                $stmt->bind_param('ssi' ,$_POST['auteur'] , $_POST['message'] , $_GET['id_sujet']);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            }
            if ($stmt = $con->prepare('UPDATE forum_subjects SET last_reponse="'.$date.'" WHERE id="'.$_GET['id_sujet'].'"')) {
                $stmt->execute();
                $stmt->close();
            }
            $_SESSION['page'] = "./php/forum/read_subject.php?id_sujet=".$_GET['id_sujet'];
            header('Location: ../../index.php');
            exit;
        }
    }
}
?>

<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['page'] = "./php/forum/insert_reponse.php?id_sujet=".$_GET['id_sujet'];
?>
<form action="./php/forum/insert_reponse.php?id_sujet=<?php echo $_GET['id_sujet']; ?>" method="post">
<table>
<tr><td>
<b>Auteur :</b>
</td><td>
<input type="text" name="auteur" maxlength="30" size="50" value="<?=$_SESSION['name'] ?>" required>
</td></tr><tr><td>
<b>Message :</b>
</td><td>
<textarea name="message" cols="50" rows="10" required><?php if (isset($_POST['message'])) echo htmlentities(trim($_POST['message'])); ?></textarea>
</td></tr><tr><td><td align="right">
<input type="submit" name="submit" value="Poster">
</td></tr></table>
</form>
<?php
if (isset($erreur)) echo '<br /><br />',$erreur;
?>