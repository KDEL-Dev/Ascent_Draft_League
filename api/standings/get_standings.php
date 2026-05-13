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
        users.team_mascot_pkmn,
        SUM(matchup.winner_active_user_id = active_users.id) AS wins,
        SUM(
            matchup.winner_active_user_id IS NOT NULL 
            AND matchup.winner_active_user_id != active_users.id
        ) AS losses,
        COALESCE((
            SELECT SUM(mps.kills) - SUM(mps.deaths)
            FROM match_pokemon_stats mps
            JOIN matchup m ON mps.matchup_id = m.id
            WHERE mps.active_user_id = active_users.id
            AND m.season_id = ?
        ), 0) AS differential
    FROM active_users
    JOIN users ON active_users.user_id = users.id
    LEFT JOIN matchup 
        ON matchup.season_id = active_users.season_id
        AND (
            matchup.player1_active_user_id = active_users.id 
            OR matchup.player2_active_user_id = active_users.id
        )
    WHERE active_users.season_id = ?
    AND competitor = 'yes'
    GROUP BY active_users.id
    ORDER BY wins DESC, differential DESC;
";

// CRITICAL: Note the "ii" and $seasonId twice
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $seasonId, $seasonId);
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