<?php
    require_once '../../includes/connection.php';
    session_start();

    if (!isset($_SESSION['season_id'])) {
        echo json_encode(["error" => "No season"]);
        exit;
    }

    $seasonId = $_SESSION['season_id'];

    $stmt = $conn->prepare("
        SELECT swaps_enabled 
        FROM draft_info 
        WHERE season_id = ?
    ");
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode([
        "swaps_enabled" => (int)$result['swaps_enabled']
    ]);