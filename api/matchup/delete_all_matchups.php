<?php 

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
    // CLEAR MATCHUP INFO
    // ----------------

    $deleteStmt = $conn->prepare("
        DELETE FROM matchup
        WHERE season_id = ?
    ");
    $deleteStmt->bind_param("i",$seasonId);
    $deleteStmt->execute();
    $deleteStmt->close();

    

    echo json_encode(['success' => true]);

    $conn->close();







?>