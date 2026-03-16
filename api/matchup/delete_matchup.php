<?php
require_once __DIR__ . '/../../includes/connection.php';
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$matchId = $data['matchup_id'] ?? null;

if (!$matchId) {
    echo json_encode(["status"=>"error","message"=>"Matchup ID missing"]);
    exit;
}

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("DELETE FROM match_pokemon_stats WHERE matchup_id = ?");
    $stmt->bind_param("i", $matchId);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM matchup WHERE id = ?");
    $stmt->bind_param("i", $matchId);
    $stmt->execute();

    $conn->commit();
    echo json_encode(["status"=>"success"]);
} catch(Exception $e) {
    $conn->rollback();
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}