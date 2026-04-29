<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/connection.php';
session_start();

if (!isset($_SESSION['user_id']) || 
    !in_array($_SESSION['role'], ['admin', 'owner'])) 
{
    echo json_encode(["status" => "error", "error" => "Unauthorized"]);
    exit;
}

$seasonId = $_SESSION['season_id'] ?? null;
$swapCount = $_POST['swapCount'] ?? null;

if ($swapCount === null || $swapCount < 0) {
    echo json_encode(["status" => "error", "error" => "Invalid swap value"]);
    exit;
}

// Update ALL users in this season
$stmt = $conn->prepare("
    UPDATE active_users
    SET swaps_remaining = ?
    WHERE season_id = ?
");

$stmt->bind_param("ii", $swapCount, $seasonId);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Swaps reset successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "error" => "Failed to update swaps"
    ]);
}

$stmt->close();
$conn->close();

header("Location: ../../admin.php?swaps=updated");
exit;