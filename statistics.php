<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();


    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;

    require_once __DIR__ . '/includes/connection.php';

    /*
        STATS QUERY
    */
    $sql = "
    SELECT 
        u.team_name,
        u.team_mascot_pkmn,
        sd.name AS pokemon_name,
        SUM(mps.kills) AS total_kills,
        SUM(mps.deaths) AS total_deaths,
        SUM(mps.used) AS total_used
    FROM match_pokemon_stats mps
    JOIN roster_pkmn rp 
        ON mps.roster_pkmn_id = rp.id
    JOIN showdown_pkmn sd 
        ON rp.showdown_pkmn = sd.id
    JOIN active_users au 
        ON mps.active_user_id = au.id
    JOIN users u 
        ON au.user_id = u.id
    JOIN matchup m 
        ON mps.matchup_id = m.id
    WHERE m.season_id = ?
    GROUP BY u.team_name, u.team_mascot_pkmn, sd.name
    ORDER BY u.team_name, total_kills DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();
    $result = $stmt->get_result();

    /*
        GROUP DATA BY TEAM
    */
    $teams = [];

    while ($row = $result->fetch_assoc()) {
        $teams[$row['team_name']]['mascot'] = $row['team_mascot_pkmn'];
        $teams[$row['team_name']]['pokemon'][] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/Ascent-White.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/Ascent-White.png">
    <script src="assets/js/script.js"></script>

    <title>Ascent - Statistics</title>
</head>
<body>
    <button id="hamburgerBtn">☰</button>
    
<div class="pageLayout">

    <?php include 'includes/navbar.php';?> 

    <div class="pageContent">

        <header class="headerCont">

            <?php include 'includes/season_setting_header.php';?>
            
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
                <div class="pageTitle">Statistics</div>
                <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
            </div>
        </header>

        <div>
            <main>
                <section class="contentCont">
                    <section class="statsCont">

                        <?php if (!empty($teams)): ?>
                            <?php foreach ($teams as $teamName => $teamData): ?>

                                <section class="statsBox">
                                    <table>

                                        <thead>
                                            <tr>
                                                <th colspan="4" class="statsNameBox">
                                                    <?= htmlspecialchars($teamName . ' ' . $teamData['mascot']); ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 55%;">Pokemon</th>
                                                <th style="width: 15%;">Kills</th>
                                                <th style="width: 15%;">Deaths</th>
                                                <th style="width: 15%;">Usage</th>
                                            </tr>
                                        </thead>

                                        <tbody class="statsBoxTblBody">
                                            <?php foreach ($teamData['pokemon'] as $pkmn): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($pkmn['pokemon_name']); ?></td>
                                                    <td><?= $pkmn['total_kills']; ?></td>
                                                    <td><?= $pkmn['total_deaths']; ?></td>
                                                    <td><?= $pkmn['total_used']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                </section>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <section id="emptyMatches">
                                No matches have been played yet
                            </section>

                        <?php endif; ?>

                    </section>
                </section>
            </main>

            <?php include 'includes/footer.php'; ?>
        </div>

    </div>

</div>
</body>
</html>