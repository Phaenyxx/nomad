<!DOCTYPE html>
<html>

<head>
    <title>Create a Character</title>
</head>

<body>

    <h2>Create a Character</h2>
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
<?php
if (count($_SESSION['msg']) > 0): ?>
    <script type="text/javascript">
        print_message(<?php echo json_encode($_SESSION['msg']); ?>);
    </script>
    <?php
    array_splice($_SESSION['msg'], 0);
endif; ?>