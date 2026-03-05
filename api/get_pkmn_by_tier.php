<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/connection.php';

try {
    $seasonId = 12; // Or dynamically from session or query

   $stmt = $conn->prepare("
    SELECT s.id, s.name, s.type1, s.type2, t.tier
    FROM showdown_pkmn s
    LEFT JOIN pkmn_tier t
        ON s.id = t.showdown_pkmn_id AND t.season_id = ?
    WHERE t.tier != 'Uber'
");
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();
    $result = $stmt->get_result();

    $pkmnList = [];
    while ($row = $result->fetch_assoc()) {
        $pkmnList[] = $row;
    }

    echo json_encode($pkmnList);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>