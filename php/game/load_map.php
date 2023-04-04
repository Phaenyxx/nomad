<?php

function display_cell($cell)
{
    if ($cell) {
        $character = isset($cell['character']) ? $cell['character'] : 0;
        echo '<td hauteur="' . $cell['hauteur'] . '" biome="' . $cell['biome'] . '">';
        if ($character == 1) {
            echo '<img class=perso src="./assets/sprites/perso.png" />';
        } else if ($character == 2) {
            echo '<img class=perso src="./assets/sprites/other.png" />';
        }
    } else {
        echo '<td>';
    }
    echo '</td>';
}

function loadmap($distance = 5, $mode = "map")
{
    if (!isset($_SESSION['character']['position_x']) || !isset($_SESSION['character']['position_y'])) {
        return;
    }
    $x = $_SESSION['character']['position_x'];
    $y = $_SESSION['character']['position_y'];

    $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $stmt = $con->prepare("SELECT * FROM map WHERE position_x BETWEEN ?-? AND ?+? AND position_y BETWEEN ?-? AND ?+? ORDER BY position_y DESC, position_x ASC");
    $stmt->bind_param('iiiiiiii', $x, $distance, $x, $distance, $y, $distance, $y, $distance);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[$row['position_y']][$row['position_x']] = $row;
    }

    if ($mode == "map") {
        $persos_stmt = $con->prepare("SELECT * FROM persos WHERE position_x BETWEEN ?-? AND ?+? AND position_y BETWEEN ?-? AND ?+?");
        $persos_stmt->bind_param('iiiiiiii', $x, $distance, $x, $distance, $y, $distance, $y, $distance);
        $persos_stmt->execute();
        $persos_result = $persos_stmt->get_result();

        while ($perso = $persos_result->fetch_assoc()) {
            $i = $perso['position_y'];
            $j = $perso['position_x'];
            if (isset($rows[$i]) && isset($rows[$i][$j])) {
                if ($i == $y && $j == $x) {
                    $rows[$i][$j]['character'] = 1; // player's character
                } else {
                    $rows[$i][$j]['character'] = 2; // other character
                }
            }
        }
    }
    echo '<table id="' . $mode . '">';
    for ($i = $y - $distance; $i <= $y + $distance; $i++) {
        echo '<tr>';
        for ($j = $x - $distance; $j <= $x + $distance; $j++) {
            if (isset($rows[$i]) && isset($rows[$i][$j])) {
                display_cell($rows[$i][$j]);
            } else {
                display_cell(null);
            }
        }
        echo '</tr>';
    }
    echo '</table>';
}
?>