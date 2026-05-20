<?php
    session_start();
    require_once __DIR__ . '/../../includes/connection.php';

    $seasonId = $_SESSION['season_id'] ?? null;

    if (!$seasonId) {
        echo json_encode([]);
        exit;
    }

    // 1. Fetch all active competitors in this season to map their IDs to Names
    $teamsQuery = "
        SELECT au.id AS active_user_id, u.team_name 
        FROM active_users au
        JOIN users u ON u.id = au.user_id
        WHERE au.season_id = ? AND au.competitor = 'yes'
    ";
    $teamsStmt = $conn->prepare($teamsQuery);
    $teamsStmt->bind_param("i", $seasonId);
    $teamsStmt->execute();
    $allTeams = $teamsStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // 2. Fetch all matches that HAVE already been played this season
    $playedQuery = "
        SELECT player1_active_user_id, player2_active_user_id 
        FROM matchup 
        WHERE season_id = ?
    ";
    $playedStmt = $conn->prepare($playedQuery);
    $playedStmt->bind_param("i", $seasonId);
    $playedStmt->execute();
    $playedMatches = $playedStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Map out who has already played whom for O(1) lightning-fast lookups
    $playedMap = [];
    foreach ($playedMatches as $match) {
        $p1 = $match['player1_active_user_id'];
        $p2 = $match['player2_active_user_id'];
        $playedMap[$p1][$p2] = true;
        $playedMap[$p2][$p1] = true;
    }

    // 3. Your Standings Query (Added 'active_users.id AS active_user_id' to the SELECT)
    $sql = "
    SELECT 
        active_users.id AS active_user_id,
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

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $seasonId, $seasonId);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) 
    {
        $row['diff'] = $row['wins'] - $row['losses'];
        
        // --- Added: Calculate Remaining Matches ---
        $currentTeamId = $row['active_user_id'];
        $remainingOpponents = [];

        foreach ($allTeams as $opponent) {
            $oppId = $opponent['active_user_id'];

            // Can't play yourself!
            if ($currentTeamId == $oppId) continue;

            // If no match is found in our map history, they still need to play
            if (!isset($playedMap[$currentTeamId][$oppId])) {
                $remainingOpponents[] = $opponent['team_name'];
            }
        }

        // Attach the unplayed list to the payload
        $row['remaining_opponents'] = $remainingOpponents;
        // ------------------------------------------

        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>