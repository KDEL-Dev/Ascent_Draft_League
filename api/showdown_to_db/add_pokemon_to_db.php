<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/connection.php';

$showdownData = json_decode(file_get_contents(__DIR__.'/../../showdownData/pokedex.json'), true);

$inserted = 0;

$stmt = $conn->prepare("
    INSERT INTO showdown_pkmn (showdown_id, name, type1, type2, created_at)
    VALUES (?, ?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE name = VALUES(name), type1 = VALUES(type1), type2 = VALUES(type2)
");

foreach ($showdownData as $pkmnKey => $pkmn) {

    // Skip illegal/nonstandard Pokémon
    if (isset($pkmn['isNonstandard']) && $pkmn['isNonstandard'] !== false) continue;
    if (isset($pkmn['isCosmeticForme']) && $pkmn['isCosmeticForme'] === true) continue;

    // Get numeric ID
    if (isset($pkmn['num'])) {
        $num = $pkmn['num'];
    } elseif (isset($pkmn['baseSpecies']) && isset($showdownData[strtolower($pkmn['baseSpecies'])]['num'])) {
        $num = $showdownData[strtolower($pkmn['baseSpecies'])]['num'];
    } else {
        error_log("Skipping $pkmnKey: no num available");
        continue;
    }

    $name = $pkmn['name'] ?? 'Unknown';
    $type1 = $pkmn['types'][0] ?? null;
    $type2 = $pkmn['types'][1] ?? null;
    $form = $pkmn['forme'] ?? ($pkmn['baseForme'] ?? 'Base');
    $showdownKey = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));

    // Prepare & execute statement inside the loop
    $stmt = $conn->prepare("
        INSERT INTO showdown_pkmn
        (showdown_id, name, showdown_key, type1, type2, form, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            name = VALUES(name), 
            type1 = VALUES(type1), 
            type2 = VALUES(type2), 
            form = VALUES(form), 
            showdown_key = VALUES(showdown_key)
    ");
    $stmt->bind_param("isssss", $num, $name, $showdownKey, $type1, $type2, $form);
    $stmt->execute();
    $stmt->close(); // close after execution

    $inserted++;
}

// Don't close $stmt here, it's already closed in the loop
$conn->close();

echo json_encode(['status' => 'success', 'inserted' => $inserted]);