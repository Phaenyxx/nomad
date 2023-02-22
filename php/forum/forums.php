<?php
if (!isset($_SESSION)) {
	session_start();
}
$_SESSION['page'] = "./php/forum/forums.php";
include('../../../config.php');
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$stmt = $con->prepare('SELECT id, auteur, titre, last_reponse FROM forum_subjects ORDER BY last_reponse DESC');
$stmt->execute();
$result = $stmt->get_result();
$nb_sujets = $result->num_rows;
if ($nb_sujets == 0) {
	echo '<h1>AUCUN SUJET</h1>';
} else {
	?>
	<table>
		<tr>
			<th class="auteur">
				Auteur
			</th>
			<th class="titre">
				Titre du sujet
			</th>
			<th class="date">
				Date dernière réponse
			</th>
		</tr>
		<?php
		while ($data = $result->fetch_array()) {
			sscanf($data['last_reponse'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
			echo '<tr>';
			echo '<td>';
			echo htmlentities(trim($data['auteur']));
			echo '</td><td>';
			echo '<a href="#" onclick="loadmain(\'./php/forum/read_subject.php?id_sujet=', $data['id'], '\')">', htmlentities(trim($data['titre'])), '</a>';
			echo '</td><td>';
			echo $jour, '-', $mois, '-', $annee, ' ', $heure, ':', $minute;
		}
}
$stmt->free_result();
$con->close();
?>
	</td>
	</tr>
</table>
<div class="buttoncontainer">
	<a class="link" href="#" onclick="loadmain('./php/forum/insert_subject.php')">Insérer un sujet </a>
	<a class="link" href="../../index.php">Retour à l'accueil</a>
</div>