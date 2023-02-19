<?php

if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['page'] = "./php/forum/read_subject.php?id_sujet=".$_GET['id_sujet'];
if (!isset($_GET['id_sujet'])) {
    echo 'Sujet non défini.';
}
else {
    ?>
    <table><tr>
    <th class="auteur">
    Auteur
    </th><th class="text">
    Messages
    </th></tr>
    <?php
    include('../../config.php');
    $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    if ($stmt = $con->prepare('SELECT auteur, message, date_reponse FROM forum_reponses WHERE correspondance_sujet = ? ORDER BY date_reponse ASC')) {
        $stmt->bind_param('i', $_GET['id_sujet']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($data = $result->fetch_array()) {
            sscanf($data['date_reponse'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
            echo '<tr>';
            echo '<td>';
            echo '<b>';
            echo htmlentities(trim($data['auteur']));
            echo '</b>';
            echo '<br />';
            echo $jour , '-' , $mois , '-' , $annee , ' ' , $heure , ':' , $minute;
            echo '</td><td>';
            echo nl2br(htmlentities(trim($data['message'])));
            echo '</td></tr>';
        }
        $stmt->free_result();
        $stmt->close();
    }
    ?>
    </table>
    <br /><br />
    <a href="#" onclick="loadmain('../php/forum/insert_reponse.php?id_sujet=<?php echo $_GET['id_sujet']; ?>')">Répondre</a>
    <?php
}
?>
<br /><br />
<a href="#" onclick="loadmain('./php/forum/forums.php')">Retour à l'accueil</a>