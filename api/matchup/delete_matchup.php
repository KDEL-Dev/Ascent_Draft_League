<?php
require_once '../../includes/connection.php';
session_start();

header('Content-Type: application/json');

// Only allow logged in users
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$matchId = $data['matchup_id'] ?? null;

if (!$matchId) {
    echo json_encode(["status"=>"error","message"=>"Matchup ID missing"]);
    exit;
}

// Optional: Only allow admin or the creator to delete
$userId = $_SESSION['user_id'];

$conn->begin_transaction();
try {
    // Delete match Pokémon stats first
    $stmt = $conn->prepare("DELETE FROM match_pokemon_stats WHERE matchup_id = ?");
    $stmt->bind_param("i", $matchId);
    $stmt->execute();

    // Delete matchup itself
    $stmt = $conn->prepare("DELETE FROM matchup WHERE id = ?");
    $stmt->bind_param("i", $matchId);
    $stmt->execute();

    $conn->commit();
    echo json_encode(["status"=>"success"]);
} catch(Exception $e) {
    $conn->rollback();
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}