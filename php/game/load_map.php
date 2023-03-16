<?php

function display_cell($cell)
{
    if ($cell) {
        echo '<td hauteur="' . $cell['hauteur'] . '" biome ="' . $cell['biome'] . '">';
        // echo '' . $cell['position_y'] . ':' . $cell['position_x'];
        echo '</td>';
    } else {
        echo '<td></td>';
    }
}
;

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
    print_r($con->error);
    $stmt->bind_param('iiiiiiii', $x, $distance, $x, $distance, $y, $distance, $y, $distance);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[$row['position_y']][$row['position_x']] = $row;
    }

    echo '<table id="'.$mode.'">';
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