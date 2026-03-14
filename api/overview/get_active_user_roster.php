<?php


header('content-type: application/json');
require_once __DIR__ . '/../../includes/connection.php';
session_start();

$seasonId = $_SESSION['season_id'] ?? null;
$user = $_SESSION['user_id'] ?? null;

$sql = "
    SELECT showdown_pkmn.name, users.gamerTag
    FROM showdown_pkmn
    JOIN roster_pkmn
    ON roster_pkmn.showdown_pkmn = showdown_pkmn.id
    JOIN active_users
    ON active_users.id = roster_pkmn.active_user
    JOIN users
    ON users.id = active_users.user_id
    WHERE roster_pkmn.season_id = ?
    AND roster_pkmn.is_active = 1
    AND users.id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is",$seasonId,$user);
$stmt->execute();
$result = $stmt->get_result();

if(!$result)
    {
        die(json_encode(['error' => $conn->error]));
    }

$userRoster = [];
while ($row = $result->fetch_assoc())
    {
        //DONT FORGET TO ADD []! This means it will append result to the array.
        //I keep forgetting it so it only returns 1 result!
        $userRoster[] = $row['name'];
    }

echo json_encode($userRoster);

$stmt->close();
$conn->close();



?>