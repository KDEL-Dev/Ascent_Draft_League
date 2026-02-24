<?php
ini_set('display_errors', 0); // hide warnings from breaking JSON
session_start();

header('Content-Type: application/json');

include __DIR__ . '/../includes/connection.php';

try {
    // 1. Get all active users and join to users table to get gamertag
    $sql = "SELECT au.id AS active_id, u.gamertag 
            FROM active_users au
            JOIN users u ON au.user_id = u.id"; // assuming active_users.user_id points to users.id
    $result = $conn->query($sql);
    if (!$result) throw new Exception("Error fetching users: " . $conn->error);

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row; // store id + gamertag
    }

    // 2. Shuffle users
    shuffle($users);

    // 3. Update draft_pick
    foreach ($users as $index => $user) {
        $draft_pick = $index + 1; // 1-based pick
        $res = $conn->query("UPDATE active_users SET draft_pick = $draft_pick WHERE id = {$user['active_id']}");
        if (!$res) throw new Exception("Error updating draft pick: " . $conn->error);
    }

    // 4. Return ordered list of gamertags
    $order = array_map(fn($u) => $u['gamertag'], $users);
    echo json_encode($order);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}