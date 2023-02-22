<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['submit']) && $_POST['submit'] == 'Poster') {
    if (!isset($_POST['auteur']) || !isset($_POST['message']) || !isset($_GET['id_sujet'])) {
        $erreur = 'Les variables nécessaires au script ne sont pas définies.';
    } else {
        if (empty($_POST['auteur']) || empty($_POST['message']) || empty($_GET['id_sujet'])) {
            $erreur = 'Au moins un des champs est vide.';
        } else {
            include('../../../config.php');
            $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
            if (mysqli_connect_errno()) {
                exit('Failed to connect to MySQL: ' . mysqli_connect_error());
            }
            $date = date("Y-m-d H:i:s");
            include('./img_upload.php');
            $image_name = handle_uploaded_image();
            if ($stmt = $con->prepare('INSERT INTO forum_subjects VALUES("", ?, ?, ?, ?)')) {
                $stmt->bind_param("ssss", $_POST['auteur'], $_POST['titre'], $_POST['tag'], $date);
                $stmt->execute();
                $id_sujet = $con->insert_id;
                if ($image_name) {
                    $stmt2 = $con->prepare('INSERT INTO forum_reponses VALUES("", ?, ?, ?, ?, ?)');
                    $stmt2->bind_param("ssssi", $_POST['auteur'], $_POST['message'], $image_name, $date, $_GET['id_sujet']);
                } else {
                    $stmt2 = $con->prepare('INSERT INTO forum_reponses VALUES("", ?, ?, NULL, ?, ?)');
                    $stmt2->bind_param("sssi", $_POST['auteur'], $_POST['message'], $date, $_GET['id_sujet']);
                }
                $stmt2->execute();
            }

            // Update the last reply date of the forum subject
            if ($stmt = $con->prepare('UPDATE forum_subjects SET last_reponse="' . $date . '" WHERE id="' . $_GET['id_sujet'] . '"')) {
                $stmt->execute();
                $stmt->close();
            }

            $_SESSION['page'] = "./php/forum/read_subject.php?id_sujet=" . $_GET['id_sujet'];
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

$_SESSION['page'] = "./php/forum/insert_reponse.php?id_sujet=" . $_GET['id_sujet'];

include('../../../config.php');
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if ($stmt = $con->prepare('SELECT auteur, message, filename, date_reponse FROM forum_reponses WHERE correspondance_sujet = ? ORDER BY date_reponse DESC')) {
    $stmt->bind_param('i', $_GET['id_sujet']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_array();
    sscanf($data['date_reponse'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
    echo '<table><tr>';
    echo '<th colspan="2"> Réponse à :</th></tr>';
    echo '<tr><td class="auteur">';
    echo '<b>';
    echo htmlentities(trim($data['auteur']));
    echo '</b>';
    echo '<br />';
    echo $jour, '-', $mois, '-', $annee, ' ', $heure, ':', $minute;
    echo '</td><td class="message">';
    if (!empty($data['filename'])) {
        echo '<div class="img-msg">';
        echo nl2br(htmlentities(trim($data['message'])));
        $image_path = './uploads/' . $data['filename'];
        echo '<a href="'. $image_path.'"><img src="' . $image_path . '"></a></div>';
    } else {
    echo nl2br(htmlentities(trim($data['message'])));
    }
    echo '</td></tr></table>';

    $stmt->free_result();
    $stmt->close();
}
?>
<form action="./php/forum/insert_reponse.php?id_sujet=<?php echo $_GET['id_sujet']; ?>" method="post"
    enctype="multipart/form-data">
    <table>
        <tr>
            <td><b>Auteur :</b></td>
            <td><input type="text" name="auteur" maxlength="30" size="50" value="<?= $_SESSION['name'] ?>" required>
            </td>
        </tr>
        <tr>
            <td><b>Message :</b></td>
            <td><textarea name="message" cols="50" rows="10" required><?php if (isset($_POST['message']))
                echo htmlentities(trim($_POST['message'])); ?></textarea>
            </td>
        </tr>
        <tr>
            <td><b>Image :</b></td>
            <td>
                <button type="button" id="toggle-upload">Ajouter une image</button>
                <input type="file" name="image" id="upload-input" accept="image/*" style="display: none;">
            </td>
        </tr>
        <tr>
            <td></td>
            <td align="right"><input type="submit" name="submit" value="Poster"></td>
        </tr>
    </table>
</form>

<div class="buttoncontainer">
    <a href="#" class="link"
        onclick="loadmain('./php/forum/read_subject.php?id_sujet=<?php echo $_GET['id_sujet']; ?>')">Retour</a>
</div>