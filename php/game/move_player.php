<?php
session_start();
include_once('../../../config.php');
include_once('./char_info.php');


$position_x = $_SESSION['character']['position_x'];
$position_y = $_SESSION['character']['position_y'];

$direction = filter_input(INPUT_POST, 'direction', FILTER_SANITIZE_STRING);

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ($direction === 'up') {
    $new_position_x = $position_x;
    $new_position_y = $position_y - 1;
} elseif ($direction === 'down') {
    $new_position_x = $position_x;
    $new_position_y = $position_y + 1;
} elseif ($direction === 'left') {
    $new_position_x = $position_x - 1;
    $new_position_y = $position_y;
} elseif ($direction === 'right') {
    $new_position_x = $position_x + 1;
    $new_position_y = $position_y;
} else {
    exit('bad direction');
}

$stmt = $con->prepare("SELECT m1.hauteur AS current_height, m2.hauteur AS new_height FROM map m1, map m2 WHERE m1.position_x = ? AND m1.position_y = ? AND m2.position_x = ? AND m2.position_y = ?");
$stmt->bind_param("iiii", $position_x, $position_y, $new_position_x, $new_position_y);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_height = $row['current_height'];
    $new_height = $row['new_height'];
    if (abs($new_height - $current_height) <= 1 && $new_height != 0) {

        $_SESSION['character']['position_x'] = $new_position_x;
        $_SESSION['character']['position_y'] = $new_position_y;
        $stmt = $con->prepare("UPDATE persos SET position_x = ?, position_y = ? WHERE username = ?");
        $stmt->bind_param("iis", $new_position_x, $new_position_y, $_SESSION['name']);
        $stmt->execute();
    }
}
$stmt->close();
$con->close();
?>

<form action="move_player.php" method="post">
    <label for="direction">Your Variable:</label>
    <input type="text" id="direction" name="direction">
    <input type="submit" value="Submit">
</form>