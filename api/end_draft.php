<?php
// end_draft.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/connection.php';

$seasonId = $_SESSION['season_id'] ?? 1;
$userId = $_SESSION['user_id'] ?? null;
$isAdmin = $_SESSION['is_admin'] ?? 0; // assumes you have an admin flag in session

if (!$userId || !$isAdmin) {
    http_response_code(403);
    echo json_encode(['error' => 'Only admins can end the draft']);
    exit;
}

// Check if draft exists
$draftRes = mysqli_query($conn, "SELECT draft_finished FROM draft_info WHERE season_id = $seasonId");
$draftRow = mysqli_fetch_assoc($draftRes);

if (!$draftRow) {
    http_response_code(404);
    echo json_encode(['error' => 'Draft not found']);
    exit;
}

// Already finished?
if ($draftRow['draft_finished']) {
    echo json_encode(['status' => 'already_finished', 'message' => 'Draft is already finished']);
    exit;
}

// End the draft
$updateRes = mysqli_query($conn, "UPDATE draft_info SET draft_finished = 1 WHERE season_id = $seasonId");

if ($updateRes) {
    echo json_encode(['status' => 'success', 'message' => 'Draft has been ended by admin']);
} else {
    http_response_code(500);
    echo json_encode(['error' => mysqli_error($conn)]);
}

$conn->close();