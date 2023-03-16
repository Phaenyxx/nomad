<?php 
if (!isset($_SESSION)) {
    session_start();
include_once('../../../config.php');
include('./char_info.php');
}

$position_x = $_SESSION['character']['position_x'];
$position_y = $_SESSION['character']['position_y'];

$direction = filter_input(INPUT_POST, 'direction', FILTER_SANITIZE_STRING);

// Step 3: Create a mysqli connection to the database using appropriate database credentials
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Step 4: Prepare and execute a SELECT query on the 'map' table to retrieve the current cell and the cell corresponding to the player's new position based on the direction they want to move in
if ($direction === 'up') {
    $new_position_x = $position_x;
    $new_position_y = $position_y + 1;
} elseif ($direction === 'down') {
    $new_position_x = $position_x;
    $new_position_y = $position_y - 1;
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

// Step 5: Check if the query returned a result and the difference in height is not more than 1
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_height = $row['current_height'];
    $new_height = $row['new_height'];
    if (abs($new_height - $current_height) <= 1 && $new_height != 0) {
        // Player can move in the specified direction
        // Update the position_x and position_y values in the $_SESSION['character'] variable
        $_SESSION['character']['position_x'] = $new_position_x;
        $_SESSION['character']['position_y'] = $new_position_y;
    } else {
        // Player cannot move in the specified direction due to height difference
    }
} else {
    // Player cannot move in the specified direction due to invalid position
}

$stmt->close();
$con->close();
?>

<form action="move_player.php" method="post">
    <label for="direction">Your Variable:</label>
    <input type="text" id="direction" name="direction">
    <input type="submit" value="Submit">
</form>