<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/connection.php';
session_start();

$seasonId = $_SESSION['season_id'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "
        SELECT users.id, users.email, users.team_name, users.team_mascot_pkmn,
               active_users.role, active_users.competitor, active_users.season_id, users.created_at
        FROM users
        LEFT JOIN active_users
        ON users.id = active_users.user_id
        AND active_users.season_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();
    $result = $stmt->get_result();

    $userInfo = [];
    while ($row = $result->fetch_assoc()) {
        $userInfo[] = $row;
    }

    echo json_encode($userInfo);
    $stmt->close();
}

elseif ($method === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $userId = $input['user_id'] ?? null;
    $role = $input['role'] ?? 'user';
    $competitor = $input['competitor'] ?? 'no';
    $season_id = $input['season_id'] ?? $seasonId;

    if (!$userId || !$season_id) {
        echo json_encode(['success' => false, 'error' => 'Missing user or season ID']);
        exit;
    }

    // Check if the user already exists in this season
    $check = $conn->prepare("SELECT * FROM active_users WHERE user_id = ? AND season_id = ?");
    $check->bind_param("ii", $userId, $season_id);
    $check->execute();
    $resultCheck = $check->get_result();

    if ($resultCheck->num_rows > 0) {
        // Update role and competitor
        $update = $conn->prepare("UPDATE active_users SET role = ?, competitor = ? WHERE user_id = ? AND season_id = ?");
        $update->bind_param("ssii", $role, $competitor, $userId, $season_id);
        if ($update->execute()) {
            echo json_encode(['success' => true, 'action' => 'updated']);
        } else {
            echo json_encode(['success' => false, 'error' => $update->error]);
        }
        $update->close();
    } else {
        // Insert new row
        $insert = $conn->prepare("INSERT INTO active_users (user_id, season_id, role, competitor) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiss", $userId, $season_id, $role, $competitor);
        if ($insert->execute()) {
            echo json_encode(['success' => true, 'action' => 'inserted']);
        } else {
            echo json_encode(['success' => false, 'error' => $insert->error]);
        }
        $insert->close();
    }

    $check->close();
}

$conn->close();