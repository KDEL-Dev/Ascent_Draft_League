<?php
header('Content-Type: application/json');
require_once '../../includes/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$matchupId = $_GET['matchup_id'] ?? null;
if (!$matchupId) {
    echo json_encode(["status" => "error", "message" => "No matchup ID"]);
    exit;
}

// Get matchup
$stmt = $conn->prepare("SELECT * FROM matchup WHERE id = ?");
$stmt->bind_param("i", $matchupId);
$stmt->execute();
$matchup = $stmt->get_result()->fetch_assoc();

// Team 1
$stmt1 = $conn->prepare("
    SELECT mps.*, sd.name AS pokemon_name
    FROM match_pokemon_stats mps
    JOIN roster_pkmn rp ON mps.roster_pkmn_id = rp.id
    JOIN showdown_pkmn sd ON rp.showdown_pkmn = sd.id
    WHERE mps.matchup_id = ? AND rp.active_user = ?
");
$stmt1->bind_param("ii", $matchupId, $matchup['player1_active_user_id']);
$stmt1->execute();
$team1 = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);

// Team 2
$stmt2 = $conn->prepare("
    SELECT mps.*, sd.name AS pokemon_name
    FROM match_pokemon_stats mps
    JOIN roster_pkmn rp ON mps.roster_pkmn_id = rp.id
    JOIN showdown_pkmn sd ON rp.showdown_pkmn = sd.id
    WHERE mps.matchup_id = ? AND rp.active_user = ?
");
$stmt2->bind_param("ii", $matchupId, $matchup['player2_active_user_id']);
$stmt2->execute();
$team2 = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status" => "success",
    "matchup" => $matchup,
    "team1" => $team1,
    "team2" => $team2
]);