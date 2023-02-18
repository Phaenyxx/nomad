<?php
print_r($_GET);
print_r($_POST);
session_start();
// on teste si le formulaire a été soumis
if (isset ($_POST['submit']) && $_POST['submit']=='Poster') {
    // on teste le contenu de la variable $auteur
    if (!isset($_POST['auteur']) || !isset($_POST['message']) || !isset($_GET['id_sujet'])) {
        $erreur = 'Les variables nécessaires au script ne sont pas définies.';
    }
    else {
        if (empty($_POST['auteur']) || empty($_POST['message']) || empty($_GET['id_sujet'])) {
            $erreur = 'Au moins un des champs est vide.';
        }
        // si tout est bon, on peut commencer l'insertion dans la base
        else {

            // on se connecte à notre base de données
            $DATABASE_HOST = 'localhost';
            $DATABASE_USER = 'root';
            $DATABASE_PASS = '';
            $DATABASE_NAME = 'nomad_login';
            // Try and connect using the info above.
            $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
            if ( mysqli_connect_errno() ) {
                // If there is an error with the connection, stop the script and display the error.
                exit('Failed to connect to MySQL: ' . mysqli_connect_error());
            }
            // on recupere la date de l'instant présent
            $date = date("Y-m-d H:i:s");
            // préparation de la requête d'insertion (table forum_reponses)
            if ($stmt = $con->prepare('INSERT INTO forum_reponses VALUES("", ? , ? , "'.$date.'" , ?)')) {
                $stmt->bind_param('ssi' ,$_POST['auteur'] , $_POST['message'] , $_GET['id_sujet']);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            }
            // préparation de la requête de modification de la date de la dernière réponse postée (dans la table forum_sujets)
            if ($stmt = $con->prepare('UPDATE forum_subjects SET last_reponse="'.$date.'" WHERE id="'.$_GET['id_sujet'].'"')) {
                $stmt->execute();
                $stmt->close();
            }
            // on redirige vers la page de lecture du sujet en cours
            $_SESSION['page'] = "./php/forum/read_subject.php?id_sujet=".$_GET['id_sujet'];
            header('Location: ../../index.php');
            
            // on termine le script courant
            exit;
        }
    }
}
?>

<?php
session_start();
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