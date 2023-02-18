<?php
if (isset ($_POST['submit']) && $_POST['submit']=='Poster') {
    if (!isset($_POST['auteur']) || !isset($_POST['titre'])|| !isset($_POST['tag']) || !isset($_POST['message'])) {
        $erreur = 'Les variables nécessaires au script ne sont pas définies.';
    }
    else {
        if (empty($_POST['auteur']) || empty($_POST['titre']) || empty($_POST['message'])) {
            $erreur = 'Au moins un des champs est vide.';
        }
        else {
            $DATABASE_HOST = 'localhost';
            $DATABASE_USER = 'root';
            $DATABASE_PASS = '';
            $DATABASE_NAME = 'nomad_login';
            $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
            if ( mysqli_connect_errno() ) {
                exit('Failed to connect to MySQL: ' . mysqli_connect_error());
            }
            $date = date("Y-m-d H:i:s");
            if ($stmt = $con->prepare('INSERT INTO forum_subjects VALUES("", "'.$con->escape_string($_POST['auteur']).'", "'.$con->escape_string($_POST['titre']).'", "'.$con->escape_string($_POST['tag']).'" ,"'.$date.'")')) {
                $stmt->execute();
                $id_sujet = $con->insert_id;
                $stmt2 = $con->prepare('INSERT INTO forum_reponses VALUES("", "'.$con->escape_string($_POST['auteur']).'", "'.$con->escape_string($_POST['message']).'", "'.$date.'", "'.$id_sujet.'")');
                $stmt2->execute();
                $stmt->close;
            }
            header('Location: ./forums.php');
            exit;
        }
    }
}
?>

<?php
session_start();
$_SESSION['page'] = "./php/forum/insert_subject.php";
// Change this to your connection info.
?>
<html>
<head>
<title>Insertion d'un nouveau sujet</title>
</head>

<body>

<!-- on fait pointer le formulaire vers la page traitant les données -->
<form action="insert_subject.php" method="post">
<table>
<tr><td>
<b>Auteur</b>
</td><td>
<input type="text" name="auteur" maxlength="30" size="50" value="<?=$_SESSION['name'] ?>" required>
</td></tr><tr><td>
<b>Titre </b>
</td><td>
<input type="text" name="titre" maxlength="50" size="50" value="<?php if (isset($_POST['titre'])) echo htmlentities(trim($_POST['titre'])); ?>" required>
</td></tr><tr><td>
<b>Tag</b>
</td><td>
<select name="tag" value="<?php if (isset($_POST['tag'])) echo htmlentities(trim($_POST['tag'])); ?>" required>
    <option value="bug">Bug</option>
    <option value="general">Général</option>
    <option value="aide">Aide</option>
    <option value="lore">Lore</option>
</select>
</td></tr><tr><td>
</b>Message </b>
</td><td>
<textarea name="message" cols="50" rows="10" required><?php if (isset($_POST['message'])) echo htmlentities(trim($_POST['message'])); ?></textarea>
</td></tr><tr><td><td align="right">
<input type="submit" name="submit" value="Poster">
</td></tr></table>
</form>
<?php
// on affiche les erreurs éventuelles
if (isset($erreur)) echo '<br /><br />',$erreur;
?>
</body>
</html>