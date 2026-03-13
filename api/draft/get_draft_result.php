<?php
session_start();
header('Content-Type: application/JSON');
require_once __DIR__ . '/../../includes/connection.php';

$seasonId = $_SESSION['season_id'] ?? null;

//This took a while because you need to remember to JOIN only on id's. Don't look for names within joins
$sql = "
SELECT 
    drafted_pkmn.pick_number,
    showdown_pkmn.name, 
    pkmn_tier.tier,
    users.gamerTag
FROM drafted_pkmn
JOIN showdown_pkmn
    ON showdown_pkmn.id = drafted_pkmn.showdown_pkmn
JOIN active_users
    ON drafted_pkmn.active_user = active_users.id
JOIN users
    ON active_users.user_id = users.id
JOIN pkmn_tier
    ON showdown_pkmn.id = pkmn_tier.showdown_pkmn_id
    AND pkmn_tier.season_id = drafted_pkmn.season_id
WHERE drafted_pkmn.season_id = ?
ORDER BY drafted_pkmn.pick_number ASC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$seasonId);
$stmt->execute();
$result = $stmt->get_result();

if(!$result){
    die(json_encode(['error' => $conn->error]));
}

//create the empty array to hold the results
$picks = [];

while ($row = $result->fetch_assoc()) {
    $picks[] = $row;
}

//This only accepts one variable
echo json_encode($picks);

$stmt->close();
$conn->close();

?>