<?php
require_once '../../includes/connection.php';
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin','owner'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$seasonId = $_SESSION['season_id'];

$stmt = $conn->prepare("
    UPDATE draft_info
    SET swaps_enabled = 1 - swaps_enabled
    WHERE season_id = ?
");
$stmt->bind_param("i", $seasonId);
$stmt->execute();

echo json_encode(["status" => "success"]);