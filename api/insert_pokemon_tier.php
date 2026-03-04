<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/connection.php';

try {
    $seasonId = 12; // Or retrieve dynamically from user/session

    // Fetch all Pokémon
    $stmt = $conn->query("SELECT * FROM showdown_pkmn");
    $inserted = 0;

    // Load JSON
    $pokedex = json_decode(file_get_contents(__DIR__ . '/../showdownData/pokedex.json'), true);

    while ($row = $stmt->fetch_assoc()) {
        $showdownPkmnId = $row['id'];       // table PK
        $showdownId = $row['showdown_id'];  // numeric ID from database

        // Lookup tier by numeric ID
        $tier = null;
        foreach ($pokedex as $pkmn) {
            if (isset($pkmn['num']) && $pkmn['num'] == $showdownId) {
                $tier = $pkmn['tier'] ?? null;
                break;
            }
        }

        if ($tier === null) {
            error_log("No tier found for Pokémon with Showdown ID: $showdownId (name: {$row['name']})");
        }

        // Insert into pkmn_tier
        $stmtInsert = $conn->prepare("
            INSERT INTO pkmn_tier (showdown_pkmn_id, season_id, tier)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE tier = VALUES(tier)
        ");
        $stmtInsert->bind_param("iis", $showdownPkmnId, $seasonId, $tier);
        $stmtInsert->execute();
        $stmtInsert->close();

        $inserted++;
    }

    echo json_encode(['status' => 'success', 'inserted' => $inserted]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>