<?php
    session_start();
    require_once __DIR__ . '/../../includes/connection.php';

    header('Content-Type: application/json');

    $seasonId = $_SESSION['season_id'] ?? null;

    if (!$seasonId) {
        echo json_encode(['error' => 'Missing season']);
        exit;
    }

    // Optional: restrict to admins only
    // if ($_SESSION['role'] !== 'admin') {
    //     http_response_code(403);
    //     echo json_encode(['error' => 'Unauthorized']);
    //     exit;
    // }

    $stmt = $conn->prepare("
        UPDATE draft_info
        SET draft_started = 0
        WHERE season_id = ?
    ");

    $stmt->bind_param("i", $seasonId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['error' => 'Failed to end draft']);
    }
?>