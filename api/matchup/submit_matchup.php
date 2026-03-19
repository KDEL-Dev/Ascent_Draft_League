<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../../includes/connection.php';
session_start();

$seasonId = $_SESSION['season_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status"=>"error","message"=>"Invalid JSON input"]);
    exit;
}



$player1 = $data['player1'] ?? null;
$player2 = $data['player2'] ?? null;
$stats   = $data['stats'] ?? [];
$replayLink = $data['replayLink'] ?? null;

if (!$player1 || !$player2) {
    echo json_encode(["status"=>"error","message"=>"Player IDs missing"]);
    exit;
}

$conn->begin_transaction();

try {
    // Create matchup
    $sql = "INSERT INTO matchup (season_id, player1_active_user_id, player2_active_user_id, replay_link) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);
    $stmt->bind_param("iiis", $seasonId, $player1, $player2, $replayLink);
    if (!$stmt->execute()) throw new Exception($stmt->error);
    $matchId = $stmt->insert_id;

    // Insert Pokémon stats
    $sql = "INSERT INTO match_pokemon_stats (matchup_id, roster_pkmn_id, kills, deaths, used) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);

    foreach ($stats as $s) {
        $stmt->bind_param(
            "iiiii",
            $matchId,
            $s['roster_pkmn_id'],
            $s['kills'],
            $s['deaths'],
            $s['used']
        );
        if (!$stmt->execute()) throw new Exception($stmt->error);
    }

    $conn->commit();
    echo json_encode(["status"=>"success"]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}