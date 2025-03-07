<?php
session_start();
if (!isset($_SESSION['msg']))
  $_SESSION['msg'] = array();
function make_clickable($text)
{
    $regex = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
    return preg_replace_callback($regex, function ($matches) {
        return "<a class=\"inlink\" href={$matches[0]} target=\"_blank\" rel=\"noopener noreferrer\">{$matches[0]}</a>";
    }, $text);
}

$_SESSION['page'] = "./php/forum/read_subject.php?id_sujet=" . $_GET['id_sujet'];
if (!isset($_GET['id_sujet'])) {
    echo 'Sujet non défini.';
} else {
    ?>
    <table id="forum-msg">
        <tr>
            <th class="auteur">
                Auteur
            </th>
            <th class="text" colspan="2">
                Messages
            </th>
        </tr>
        <?php
        include_once('../../../config.php');
        $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
        if (mysqli_connect_errno()) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
        if ($stmt = $con->prepare('SELECT auteur, message, filename, date_reponse FROM forum_reponses WHERE correspondance_sujet = ? ORDER BY date_reponse ASC')) {
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
                echo $jour, '-', $mois, '-', $annee, ' ', $heure, ':', $minute;
                echo '</td><td>';
                if (!empty($data['filename'])) {
                    echo '<div class="img-msg"><span>';
                    echo nl2br(make_clickable(htmlentities(trim($data['message']))));
                    $image_path = './uploads/' . $data['filename'];
                    echo '</span><a href="' . $image_path . '" target="_blank"><img src="' . $image_path . '"></a></div>';
                } else {
                    echo nl2br(make_clickable(htmlentities(trim($data['message']))));
                }
                echo '</td></tr>';
            }
            $stmt->free_result();
            $stmt->close();
        }
}
?>
</table>
<div class="buttoncontainer">
    <a href="#" class="link"
        onclick="loadmain('./php/forum/insert_reponse.php?id_sujet=<?php echo $_GET['id_sujet']; ?>')">Répondre</a>
    <a href="#" class="link" onclick="loadmain('./php/forum/forums.php')">Retour à l'accueil</a>
</div>