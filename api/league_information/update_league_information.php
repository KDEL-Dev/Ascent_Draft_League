<?php

    require_once __DIR__ . '/includes/connection.php';

    $sql = "SELECT * FROM league_information WHERE season_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
    $row = [
        'about' => '',
        'rules' => '',
    ];
}

?>