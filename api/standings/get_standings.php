<?php
    session_start();
    require_once __DIR__ . '/../../includes/connection.php';

    $seasonId = $_SESSION['season_id'] ?? null;

    if (!$seasonId) {
        echo json_encode([]);
        exit;
    }

    $sql = "
        SELECT 
        users.team_name,

        SUM(matchup.winner_active_user_id = active_users.id) AS wins,

        SUM(
            matchup.winner_active_user_id IS NOT NULL 
            AND matchup.winner_active_user_id != active_users.id
        ) AS losses

        FROM active_users

        JOIN users 
            ON active_users.user_id = users.id

        LEFT JOIN matchup 
            ON matchup.season_id = active_users.season_id
            AND (
                matchup.player1_active_user_id = active_users.id 
                OR matchup.player2_active_user_id = active_users.id
            )

        WHERE active_users.season_id = ?
        AND competitor = 'yes'

        GROUP BY active_users.id
        HAVING wins + losses > 0
        ORDER BY wins DESC;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();

    $result = $stmt->get_result();

    $data = [];

    while ($row = $result->fetch_assoc()) 
    {
        $row['diff'] = $row['wins'] - $row['losses'];
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);