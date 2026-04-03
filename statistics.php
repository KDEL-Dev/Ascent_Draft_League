<?php
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
    GROUP BY u.team_name, sd.name
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
        $teams[$row['team_name']][] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Ascent - Statistics</title>
</head>
<body>
<div class="pageLayout">

    <?php include 'includes/navbar.php';?> 

    <div class="pageContent">

        <header class="headerCont">
            <div class="seasonCont">
                <div class="seasonBtn">
                    Season <?php echo htmlspecialchars($seasonId); ?>
                </div>
            </div>
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                <div class="pageTitle">Statistics</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <div>
            <main>
                <section class="contentCont">
                    <section class="statsCont">

                        <?php if (!empty($teams)): ?>
                            <?php foreach ($teams as $teamName => $pokemonList): ?>

                                <section class="statsBox">
                                    <table>

                                        <thead>
                                            <tr>
                                                <th colspan="4" class="statsNameBox">
                                                    <?= htmlspecialchars($teamName); ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 55%;">Pokemon</th>
                                                <th style="width: 15%;">Kills</th>
                                                <th style="width: 15%;">Deaths</th>
                                                <th style="width: 15%;">Usage</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($pokemonList as $pkmn): ?>
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