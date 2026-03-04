<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/connection.php';

$showdownData = json_decode(file_get_contents(__DIR__.'/../showdownData/pokedex.json'), true);

$inserted = 0;

$stmt = $conn->prepare("
    INSERT INTO showdown_pkmn (showdown_id, name, type1, type2, created_at)
    VALUES (?, ?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE name = VALUES(name), type1 = VALUES(type1), type2 = VALUES(type2)
");

foreach ($showdownData as $key => $pkmn) {
    if (isset($pkmn['isNonstandard']) && $pkmn['isNonstandard'] !== false) continue; // skip illegal/past/etc
    if (!isset($pkmn['num'])) continue; // skip forms without numeric ID

    $num = $pkmn['num'];
    $name = $pkmn['name'];
    $type1 = $pkmn['types'][0] ?? null;
    $type2 = $pkmn['types'][1] ?? null;

    $stmt->bind_param("isss", $num, $name, $type1, $type2);
    $stmt->execute();
    $inserted++;
}

$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'inserted' => $inserted]);