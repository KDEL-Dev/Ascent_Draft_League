<?php
header('Content-Type: application/json');

require_once '../../includes/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Not authenticated"
    ]);
    exit;
}


$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON input"
    ]);
    exit;
}

$matchupId = $input['matchup_id'] ?? null;
$replayLink = $input['replay_link'] ?? '';
$stats = $input['stats'] ?? [];

if (!$matchupId) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing matchup_id"
    ]);
    exit;
}

try {

    $conn->begin_transaction();

    $stmt = $conn->prepare("UPDATE matchup SET replay_link = ? WHERE id = ?");
    $stmt->bind_param("si", $replayLink, $matchupId);
    $stmt->execute();

    $stmtStats = $conn->prepare("
        UPDATE match_pokemon_stats 
        SET kills = ?, deaths = ?
        WHERE matchup_id = ? AND roster_pkmn_id = ?
    ");

    foreach ($stats as $s) {
        $rosterId = $s['roster_pkmn_id'];
        $kills = $s['kills'];
        $deaths = $s['deaths'];

        $stmtStats->bind_param("iiii", $kills, $deaths, $matchupId, $rosterId);
        $stmtStats->execute();
    }

    $conn->commit();

    echo json_encode([
        "status" => "success"
    ]);

} catch (Exception $e) {

    $conn->rollback();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}