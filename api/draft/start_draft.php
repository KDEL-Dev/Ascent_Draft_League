<?php
session_start();
require_once __DIR__ . '/../../includes/connection.php';

header('Content-Type: application/json');

$seasonId = $_SESSION['season_id'] ?? null;

if (!$seasonId) {
    echo json_encode(['error' => 'Missing season']);
    exit;
}

// Count active users
$result = $conn->prepare("
    SELECT COUNT(*) AS user_count 
    FROM active_users 
    WHERE season_id = ?
");
$result->bind_param("i", $seasonId);
$result->execute();
$row = $result->get_result()->fetch_assoc();

$userCount = (int)$row['user_count'];

// Calculate total picks
$totalPicks = $userCount * 12;

// Update draft_info
$stmt = $conn->prepare("
    UPDATE draft_info
    SET total_picks = ?, draft_started = 1
    WHERE season_id = ?
");

$stmt->bind_param("ii", $totalPicks, $seasonId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to start draft']);
}