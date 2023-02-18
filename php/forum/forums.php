<?php
session_start();
$_SESSION['page'] = "./php/forum/forums.php";
// Change this to your connection info.
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
$stmt = $con->prepare('SELECT id, auteur, titre, last_reponse FROM forum_subjects ORDER BY last_reponse DESC');
$stmt->execute();
$result = $stmt->get_result();

$nb_sujets = $result->num_rows;
echo '<a href="#" onclick="loadmain(\'./php/forum/insert_subject.php\')">Insérer un sujet </a>';

if ($nb_sujets == 0) {
	echo '<h1>AUCUN SUJET</h1>';
}
else {
	?>
	<table width="500" border="1"><tr>
	<td>
	Auteur
	</td><td>
	Titre du sujet
	</td><td>
	Date dernière réponse
	</td></tr>
	<?php
	// on va scanner tous les tuples un par un
	while ($data = $result->fetch_array()) {

	// on décompose la date
	sscanf($data['last_reponse'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);

	// on affiche les résultats
	echo '<tr>';
	echo '<td>';

	// on affiche le nom de l'auteur de sujet
	echo htmlentities(trim($data['auteur']));
	echo '</td><td>';

	// on affiche le titre du sujet, et sur ce sujet, on insère le lien qui nous permettra de lire les différentes réponses de ce sujet
	echo '<a href="#" onclick="loadmain(\'./php/forum/read_subject.php?id_sujet=' , $data['id'] , '\')">' , htmlentities(trim($data['titre'])) , '</a>';

	echo '</td><td>';

	// on affiche la date de la dernière réponse de ce sujet
	echo $jour , '-' , $mois , '-' , $annee , ' ' , $heure , ':' , $minute;
	}
	?>
	</td></tr></table>
	<a href="../../index.php">Retour à l'accueil</a>
	<?php
}

// on libère l'espace mémoire alloué pour cette requête
$stmt->free_result();
// on ferme la connexion à la base de données.
$con->close();
?>
