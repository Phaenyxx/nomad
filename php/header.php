<?php 

if (!isset($_SESSION['loggedin'])):?>
    <header>
        <nav id="nav">
            <ul>
                <li><a href="#" class="link" onclick="loadmain('./php/login.php')">Jeu</a></li>
                <li><a href="#" class="link" onclick="loadmain('./php/forums.php')">Forums</a></li>
                <li><a href="#" class="link" onclick="loadmain('./php/login.php')">Connexion</a></li>
            </ul>
        </nav>
    </header>
<?php
    else:?>
    <header>
        <nav id="nav">
            <ul>
                <li><a href="#" class="link" onclick="loadmain('./php/game.php')">Jeu</a></li>
                <li><a href="#" class="link" onclick="loadmain('./php/forums.php')">Forums</a></li>
                <li><a href="#" class="link" onclick="loadmain('./php/account.php')">Compte : <?=$_SESSION['name'] ?></a></li>
                <li><a href="./auth/logout.php" class="link">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <?php endif;
?>