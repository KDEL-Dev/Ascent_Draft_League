<?php
    header('Content-Type: application/json');
    require_once __DIR__ . '/../../includes/connection.php';
    session_start();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(["error" => "Missing id"]);
        exit;
    }

    $stmt = $conn->prepare("
        SELECT 
            s.id,
            s.name,
            t.tier
        FROM showdown_pkmn s
        LEFT JOIN pkmn_tier t 
            ON s.id = t.showdown_pkmn_id
        WHERE s.id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode($result->fetch_assoc());

    $stmt->close();
    $conn->close();
?>