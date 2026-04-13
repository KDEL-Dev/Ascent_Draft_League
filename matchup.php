<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/connection.php'; // make sure the path is correct
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$seasonId = $_SESSION['season_id'] ?? null;

if (!$seasonId) {
    die("No season_id found in session");
}

// Fetch all matchups for this season
$matchups = [];
$sql = "
SELECT 
    m.*,
    u1.team_name AS team1_name,
    u2.team_name AS team2_name
FROM matchup m
JOIN active_users au1 ON au1.id = m.player1_active_user_id
JOIN active_users au2 ON au2.id = m.player2_active_user_id
JOIN users u1 ON u1.id = au1.user_id
JOIN users u2 ON u2.id = au2.user_id
WHERE m.season_id = ?
ORDER BY m.created_at DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $seasonId);

if (!$stmt->execute()) {
    die("SQL Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

if (!$result) {
    die("Get result failed");
}

while ($row = $result->fetch_assoc()) {
    $matchups[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Ascent - Matchups</title>
</head>
<body>
    <button id="hamburgerBtn">☰</button>
    <div class="pageLayout">

        <?php include 'includes/navbar.php';?> 

        <div class="pageContent">
            <header class="headerCont">

                <?php include 'includes/season_setting_header.php';?>
                
                <div class="pageNameCont">
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                    <div class="pageTitle"> Match Ups</div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">
                        <section id="matchupCont">
                            <section class="contentBtnCont">
                                <a id="newMatchBtn" href="add_matchup.php">Add New Matchup</a>
                            </section>
                            <section id="matchupResultCont">
                                <?php if (!empty($matchups)): ?>
                                    <?php foreach ($matchups as $match): ?>
                                        <?php
                                            // Team 1 Pokémon
                                            $sql1 = "
                                                SELECT mps.*, sd.name AS pokemon_name
                                                FROM match_pokemon_stats mps
                                                JOIN roster_pkmn rp ON mps.roster_pkmn_id = rp.id
                                                JOIN showdown_pkmn sd ON rp.showdown_pkmn = sd.id
                                                WHERE mps.matchup_id = ? AND rp.active_user = ?
                                            ";
                                            $stmt1 = $conn->prepare($sql1);
                                            $stmt1->bind_param("ii", $match['id'], $match['player1_active_user_id']);
                                            $stmt1->execute();
                                            $team1Pkmn = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);

                                            // Team 2 Pokémon
                                            $sql2 = "
                                                SELECT mps.*, sd.name AS pokemon_name
                                                FROM match_pokemon_stats mps
                                                JOIN roster_pkmn rp ON mps.roster_pkmn_id = rp.id
                                                JOIN showdown_pkmn sd ON rp.showdown_pkmn = sd.id
                                                WHERE mps.matchup_id = ? AND rp.active_user = ?
                                            ";
                                            $stmt2 = $conn->prepare($sql2);
                                            $stmt2->bind_param("ii", $match['id'], $match['player2_active_user_id']);
                                            $stmt2->execute();
                                            $team2Pkmn = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
                                        ?>
                                        
                                        <section class="editDeleteMatchCont">
                                            <section class="editDeleteBtnCont">
                                                <button class="editMatchBtn" data-match-id="<?= $match['id'] ?>">Edit</button>
                                                <button class="deleteMatchBtn" data-match-id="<?= $match['id'] ?>">Delete</button>
                                            </section>
                                            <section class="matchupTitle">
                                                <h2>
                                                    <?= htmlspecialchars($match['team1_name']) ?>
                                                    vs
                                                    <?= htmlspecialchars($match['team2_name']) ?>
                                                </h2>
                                                    <section class="replayCont">
                                                    <a href="<?= htmlspecialchars($match['replay_link'] ?? '#') ?>" target="_blank">
                                                        <h3>Watch Replay</h3>
                                                    </a>
                                                </section>
                                            </section>
                                            
                                                <section class="matchBox">
                                                <section class="matchUpPlayerInfo">
                                                    <table class="matchUpStats">
                                                        <tr>
                                                            <th colspan="3" class="matchUpTeamName"> Team 1</th>
                                                        </tr>
                                                        <tr class="matchUpHeader">
                                                            <th >Name</th>
                                                            <th  >Kills</th>
                                                            <th >Deaths</th>
                                                        </tr>
                                                        <?php foreach ($team1Pkmn as $p): ?>
                                                            <tr>
                                                                <td class="matchName"><?= htmlspecialchars($p['pokemon_name']) ?></td>
                                                                <td class="matchKill"><?= $p['kills'] ?></td>
                                                                <td class="matchDeath"><?= $p['deaths'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td colspan="3" class="matchResultCont 
                                                                <?= $match['winner_active_user_id'] == $match['player1_active_user_id'] ? 'win' : 'loss' ?>">
                                                                
                                                                <?= $match['winner_active_user_id'] == $match['player1_active_user_id'] ? 'Win' : 'Loss' ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </section>

                                                

                                                <section class="matchUpPlayerInfo">
                                                    <table class="matchUpStats">
                                                        <tr>
                                                            <th colspan="3" class="matchUpTeamName"> Team 2</th>
                                                        </tr>
                                                        <tr class="matchUpHeader">
                                                            <th>Name</th>
                                                            <th style="width: 20%;">Kills</th>
                                                            <th style="width: 20%;">Deaths</th>
                                                        </tr>
                                                        <?php foreach ($team2Pkmn as $p): ?>
                                                            <tr>
                                                                <td class="matchName"><?= htmlspecialchars($p['pokemon_name']) ?></td>
                                                                <td class="matchKill"><?= $p['kills'] ?></td>
                                                                <td class="matchDeath"><?= $p['deaths'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td colspan="3" class="matchResultCont 
                                                                <?= $match['winner_active_user_id'] == $match['player2_active_user_id'] ? 'win' : 'loss' ?>">
                                                                
                                                                <?= $match['winner_active_user_id'] == $match['player2_active_user_id'] ? 'Win' : 'Loss' ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </section>
                                            </section>
                                        </section>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <section id="emptyMatches">
                                    No matches have been played yet
                                </section>

                            <?php endif; ?>
                            </section>
                        </section>
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>