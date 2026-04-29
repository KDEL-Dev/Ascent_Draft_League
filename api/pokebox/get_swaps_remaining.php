<?php
    require_once '../../includes/connection.php';
    session_start();

    if (!isset($_SESSION['user_id'], $_SESSION['season_id'])) {
        echo json_encode(["error" => "Not authenticated"]);
        exit;
    }

    $stmt = $conn->prepare("
        SELECT swaps_remaining
        FROM active_users
        WHERE user_id = ? AND season_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $_SESSION['user_id'], $_SESSION['season_id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode($result);