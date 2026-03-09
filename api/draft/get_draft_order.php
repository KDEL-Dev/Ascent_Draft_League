<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/connection.php';

$seasonId = $_SESSION['season_id'] ?? null;

//could of also just pasted this straight into prepare
$sql = "
    SELECT u.gamerTag
    FROM active_users au
    JOIN users u ON au.user_id = u.id
    WHERE au.draft_pick IS NOT NULL
      AND au.season_id = ? 
    GROUP BY au.user_id
    ORDER BY MIN(au.draft_pick) ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seasonId); 
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die(json_encode(['error' => $conn->error]));
}

$order = [];
while ($row = $result->fetch_assoc()) {
    $order[] = $row['gamerTag'];
}

echo json_encode($order);

$stmt->close();
$conn->close();

// DONT TOUCH THIS WORKS