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
            include('./php/forum/img_upload.php');
            $image_path = handle_uploaded_image();
            if ($stmt = $con->prepare('INSERT INTO forum_subjects VALUES("", ?, ?, ?, ?)')) {
                $stmt->bind_param("ssss", $_POST['auteur'], $_POST['titre'], $_POST['tag'], $date);
                $stmt->execute();
                $id_sujet = $con->insert_id;
                if ($image_path) {
                    $stmt2 = $con->prepare('INSERT INTO forum_reponses VALUES("", ?, ?, ?, ?, ?)');
                    $stmt2->bind_param("ssssi", $_POST['auteur'], $_POST['message'], $image_filename, $date, $_GET['id_sujet']);
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