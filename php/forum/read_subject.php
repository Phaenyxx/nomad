<?php

session_start();
$_SESSION['page'] = "./php/forum/read_subject.php";
if (!isset($_GET['id_sujet'])) {
    echo 'Sujet non défini.';
}
else {
    ?>
    <table width="500" border="1"><tr>
    <td>
    Auteur
    </td><td>
    Messages
    </td></tr>
    <?php
    // on se connecte à notre base de données
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'nomad_login';
    // Try and connect using the info above.
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    
    // on prépare notre requête
    if ($stmt = $con->prepare('SELECT auteur, message, date_reponse FROM forum_reponses WHERE correspondance_sujet = ? ORDER BY date_reponse ASC')) {
        
        // on lance la requête (mysql_query) et on impose un message d'erreur si la requête ne se passe pas bien (or die)
        $stmt->bind_param('i', $_GET['id_sujet']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // on va scanner tous les tuples un par un
        while ($data = $result->fetch_array()) {
            
            // on décompose la date
            sscanf($data['date_reponse'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
            
            // on affiche les résultats
            echo '<tr>';
            echo '<td>';
            
            // on affiche le nom de l'auteur de sujet ainsi que la date de la réponse
            echo htmlentities(trim($data['auteur']));
            echo '<br />';
            echo $jour , '-' , $mois , '-' , $annee , ' ' , $heure , ':' , $minute;
            
            echo '</td><td>';
            
            // on affiche le message
            echo nl2br(htmlentities(trim($data['message'])));
            echo '</td></tr>';
        }
        
        // on libère l'espace mémoire alloué pour cette reqête
        $stmt->free_result();
        // on ferme la connection à la base de données.
        $stmt->close();
    }
    ?>
    
    <!-- on ferme notre table html -->
    </table>
    <br /><br />
    <!-- on insère un lien qui nous permettra de rajouter des réponses à ce sujet -->
    <a href="#" onclick="loadmain('../php/forum/insert_reponse.php?id_sujet=<?php echo $_GET['id_sujet']; ?>')">Répondre</a>
    <?php
}
?>
<br /><br />
<!-- on insère un lien qui nous permettra de retourner à l'accueil du forum -->
<a href="#" onclick="loadmain('./php/forum/forums.php')">Retour à l'accueil</a>