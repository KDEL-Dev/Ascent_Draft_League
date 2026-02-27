<?php
//when checking for error uncomment below

// error_reporting(E_ALL);
// ini_set('display_errors', 1);



// This tells page to give you draft order as JSON
header('Content-Type: application/json');

//Connect to database --if I change file location, path may need to change as well
// require_once 'includes/connection.php';
require_once __DIR__ . '/../includes/connection.php';

//query
$sql = "
        SELECT users.gamerTag
        FROM active_users
        JOIN users ON active_users.user_id = users.id
        WHERE active_users.draft_pick IS NOT NULL
        GROUP BY active_users.user_id
        ORDER BY MIN(active_users.draft_pick) ASC
        ";

$result = mysqli_query($conn,$sql);

if(!$result)
    {
        // Just so I remember, $conn is a variable created in connection.php
        die(json_encode(['error' => mysqli_error($conn)]));
    }

//Creates empty Array to holder order
// Variables in php start with $ and not const/var/let
$order = [];

//Loop to place gamer_tags into array
while ($row = mysqli_fetch_assoc($result)) {
    $order[] = $row['gamerTag'];
}

//Returns JSON
echo json_encode($order);
?>