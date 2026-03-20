<?php
session_start();
require_once __DIR__ . '/../../includes/connection.php';

header('Content-Type: application/json');

$seasonId = $_SESSION['season_id'] ?? null;

if (!$seasonId) {
    echo json_encode(['error' => 'Missing season']);
    exit;
}

// Optional: restrict to admin if needed

$stmt = $conn->prepare("
    UPDATE draft_info
    SET current_pick = current_pick + 1
    WHERE season_id = ?
");
$stmt->bind_param("i", $seasonId);

if ($stmt->execute()) 
{
    echo json_encode(['status' => 'success']);
} 
else 
{
    echo json_encode(['error' => 'Failed to skip pick']);
}