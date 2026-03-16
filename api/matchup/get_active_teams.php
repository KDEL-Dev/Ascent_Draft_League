<?php

header('content-type: application/json');
require_once __DIR__ . '/../../includes/connection.php';
session_start();

$seasonId = $_SESSION['season_id'] ?? null;

if (!$seasonId) {
    echo json_encode(["error" => "Season not set"]);
    exit;
}



$sql = "
SELECT 
    au.id AS active_user_id,
    u.gamerTag
FROM active_users au
JOIN users u ON u.id = au.user_id
WHERE au.season_id = ?
ORDER BY u.gamerTag
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$seasonId);
$stmt->execute();

$result = $stmt->get_result();

$teams = [];

while($row = $result->fetch_assoc()){
    $teams[] = $row;
}

echo json_encode($teams);

?>