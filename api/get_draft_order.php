<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/connection.php';

$seasonId = $_SESSION['season_id'] ?? 1;

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
$stmt->bind_param("i", $seasonId); // now properly used
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