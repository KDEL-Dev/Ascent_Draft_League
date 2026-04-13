<?php

require_once __DIR__ . '/../../includes/connection.php';

$teamId = $_GET['active_user_id'];

$sql = "
SELECT 
    rp.id AS roster_pkmn_id,
    sp.name
FROM roster_pkmn rp
JOIN showdown_pkmn sp 
ON sp.id = rp.showdown_pkmn
WHERE rp.active_user = ?
AND rp.is_active = 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$teamId);
$stmt->execute();

$result = $stmt->get_result();

$roster = [];

while($row = $result->fetch_assoc()){
    $roster[] = $row;
}

echo json_encode($roster);