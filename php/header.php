<?php 

if (!isset($_SESSION['loggedin'])):?>
    <header>
        <nav id="nav">
            <ul>
                <li><a href="#" onclick="loadmain('./php/auth/login.php')">Jeu</a></li>
                <li><a href="#" onclick="loadmain('./php/auth/login.php')">Forums</a></li>
                <li><a href="#" onclick="loadmain('./php/auth/login.php')">Connexion</a></li>
            </ul>
        </nav>
    </header>
<?php
    else:?>
    <header>
        <nav id="nav">
            <ul>
                <li><a href="#" onclick="loadmain('./php/game/game.php')">Jeu</a></li>
                <li><a href="#" onclick="loadmain('./php/forum/forums.php')">Forums</a></li>
                <li><a href="#" onclick="loadmain('./php/account.php')">Compte : <?=$_SESSION['name'] ?></a></li>
                <li><a href="./php/auth/logout.php" class="link">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <?php endif;
?>