<?php
session_start();
if (!isset($_SESSION['msg']))
    $_SESSION['msg'] = array();
    ?>
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
