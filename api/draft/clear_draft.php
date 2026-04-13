<?php
// error_reporting(E_ALL);
// ini_set('display_errors',1);

header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/connection.php';
session_start();

$seasonId = $_SESSION['season_id'] ?? null; 

// Check to see if there is a connection
if(!$seasonId)
    {
        http_response_code(400);
        echo json_encode(['error' => 'No season connected']);
        exit;
    }

// ----------------    
// CLEAR DRAFT INFO
// ----------------

$deleteStmt = $conn->prepare("
    DELETE FROM drafted_pkmn
    WHERE season_id = ?
");
$deleteStmt->bind_param("i",$seasonId);
$deleteStmt->execute();
$deleteStmt->close();

// -----------------
// CLEAR ROSTER INFO
// -----------------
$rosterStmt = $conn->prepare("
    DELETE FROM roster_pkmn
    WHERE season_id = ?
");
$rosterStmt->bind_param("i", $seasonId);
$rosterStmt->execute();
$rosterStmt->close();

// -------------------
// UPDATE CURRENT PICK
// -------------------

$updateStmt = $conn->prepare("
    UPDATE draft_info
    SET current_pick = 1,
    total_picks = 0,
    draft_started = 0,
    draft_finished = 0
    WHERE season_id = ?
");
$updateStmt->bind_param("i",$seasonId);
$updateStmt->execute();
$updateStmt->close();

echo json_encode(['success' => true]);

$conn->close();





?>