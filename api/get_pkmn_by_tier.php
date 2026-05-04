<?php
    header('Content-Type: application/json');
    require_once __DIR__ . '/../includes/connection.php';
    session_start();

    try {
        $seasonId = $_SESSION['season_id'] ?? null;

        // Get all Pokémon and whether they've been drafted by anyone
        $stmt = $conn->prepare("
            SELECT 
                s.id, s.name, s.type1, s.type2, t.tier,
                CASE WHEN rp.showdown_pkmn IS NOT NULL THEN 1 ELSE 0 END AS drafted
            FROM showdown_pkmn s
            LEFT JOIN pkmn_tier t
                ON s.id = t.showdown_pkmn_id AND t.season_id = ?
            LEFT JOIN roster_pkmn rp
                ON rp.showdown_pkmn = s.id 
                AND rp.season_id = ?
                AND rp.is_active = 1
            WHERE t.tier != 'Uber'
        ");
        $stmt->bind_param("ii", $seasonId, $seasonId);
        $stmt->execute();
        $result = $stmt->get_result();

        $pkmnList = [];
        
        
        while ($row = $result->fetch_assoc()) {
            $row['drafted'] = $row['drafted'] == 1; // convert to boolean
            $pkmnList[] = $row;
        }

        echo json_encode($pkmnList);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

    $conn->close();
?>