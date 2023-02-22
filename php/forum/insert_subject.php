<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_POST['submit']) && $_POST['submit'] == 'Poster') {
  if (!isset($_POST['auteur']) || !isset($_POST['titre']) || !isset($_POST['tag']) || !isset($_POST['message'])) {
    $_SESSION['message'] = 'Les variables nécessaires au script ne sont pas définies.';
  } else {
    if (empty($_POST['auteur']) || empty($_POST['titre']) || empty($_POST['message'])) {
      $_SESSION['message'] = 'Au moins un des champs est vide.';
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
          $stmt2->bind_param("ssssi", $_POST['auteur'], $_POST['message'], $image_name, $date, $id_sujet);
        } else {
          $stmt2 = $con->prepare('INSERT INTO forum_reponses VALUES("", ?, ?, NULL, ?, ?)');
          $stmt2->bind_param("sssi", $_POST['auteur'], $_POST['message'], $date, $id_sujet);
        }
        $stmt2->execute();
        $stmt2->close();
        $_SESSION['page'] = "./php/forum/read_subject.php?id_sujet=" . $id_sujet;
      } else {
        $_SESSION['message'] = 'Erreur de connexion. Veuillez réessayer.';
      }
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
$_SESSION['page'] = "./php/forum/insert_subject.php";
?>
<html>

<head>
  <title>Insertion d'un nouveau sujet</title>
</head>

<body>
  <form action="./php/forum/insert_subject.php" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <td><b>Auteur</b></td>
        <td><input type="text" name="auteur" maxlength="30" size="50" value="<?= $_SESSION['name'] ?>" required></td>
      </tr>
      <tr>
        <td><b>Titre</b></td>
        <td><input type="text" name="titre" maxlength="50" size="50" value="<?php if (isset($_POST['titre']))
          echo htmlentities(trim($_POST['titre'])); ?>" required></td>
      </tr>
      <tr>
        <td><b>Tag</b></td>
        <td>
          <select name="tag" value="<?php if (isset($_POST['tag']))
            echo htmlentities(trim($_POST['tag'])); ?>" required>
            <option value="bug">Bug</option>
            <option value="general">Général</option>
            <option value="aide">Aide</option>
            <option value="lore">Lore</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><b>Message</b></td>
        <td><textarea name="message" cols="50" rows="10" required><?php if (isset($_POST['message']))
          echo htmlentities(trim($_POST['message'])); ?></textarea></td>
      </tr>
      <tr>
        <td><b>Image</b></td>
        <td>
          <button type="button" id="toggle-upload">Ajouter une image</button>
          <input type="file" name="image" id="upload-input" accept="image/*" style="display: none;">
        </td>
      </tr>
      <tr>
        <td></td>
        <td align="right">
          <input type="submit" name="submit" value="Poster">
        </td>
      </tr>
    </table>
  </form>
</body>

</html>