<!DOCTYPE html>
<html>

<head>
    <title>Create a Character</title>
</head>

<body>

    <h2>Create a Character</h2>

    <?php if (isset($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li>
                    <?= $error ?>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <div class="container">


        <div id="form_container">
            <form id="char-crea-form" action="./php/game/register_char.php" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <button type="submit">Create Character</button>
            </form>
        </div>
    </div>
</body>

</html>