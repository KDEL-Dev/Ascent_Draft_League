<?php
    header('Content-Type: application/json');
    require_once __DIR__ . '/../includes/connection.php';
    session_start();

    $seasonId = $_SESSION['season_id'] ?? null;

    // 1. Get all active users for this season who are competitors
    $sql = "SELECT user_id FROM active_users WHERE season_id = ? AND competitor = 'yes'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die(json_encode(['error' => $conn->error]));
    }

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row['user_id'];
    }

    if (empty($users)) {
        echo json_encode(['error' => 'No active competitors found for this season']);
        exit;
    }

    // 2. Shuffle them
    shuffle($users);

    // 3. Update draft_pick for this season
    foreach ($users as $index => $user_id) {
        $draft_pick = $index + 1;

        $updateStmt = $conn->prepare("
            UPDATE active_users 
            SET draft_pick = ? 
            WHERE user_id = ? AND season_id = ?
        ");
        $updateStmt->bind_param("iii", $draft_pick, $user_id, $seasonId);
        $updateStmt->execute();
        $updateStmt->close();
    }

    // 4. Return new draft order
    $orderQuery = "
        SELECT u.team_name
        FROM active_users au
        JOIN users u ON au.user_id = u.id
        WHERE au.season_id = ? AND au.competitor = 'yes'
        ORDER BY au.draft_pick ASC
    ";
    $orderStmt = $conn->prepare($orderQuery);
    $orderStmt->bind_param("i", $seasonId);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();

    $order = [];
    while ($row = $orderResult->fetch_assoc()) {
        $order[] = $row['team_name'];
    }

    echo json_encode($order);

    $orderStmt->close();
    $conn->close();