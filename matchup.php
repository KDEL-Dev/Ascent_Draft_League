<?php
require_once 'includes/connection.php'; // make sure the path is correct
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$seasonId = $_SESSION['season_id'] ?? null;

// Fetch all matchups for this season
$matchups = [];
$sql = "SELECT * FROM matchup WHERE season_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seasonId);
$stmt->execute();
$result = $stmt->get_result();
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
    <div class="pageLayout">

        <?php include 'includes/navbar.php';?> 

        <div class="pageContent">
            <header class="headerCont">
                <div class="seasonCont">
                    <div class="seasonBtn">Season <?php echo htmlspecialchars($seasonId); ?></div>
                </div>
                <div class="pageNameCont">
                    <div class="pageTitle"> Match Ups</div>
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">
                        <section id="matchupCont">
                            <section class="contentBtnCont">
                                <button><a href="add_matchup.php">Add New Matchup</a></button>
                            </section>
                            <section id="matchupResultCont">
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
                                        <button>edit</button>
                                        <button class="deleteMatchBtn" data-match-id="<?= $match['id'] ?>">Delete</button>
                                        <section class="matchBox">
                                            <section class="matchUpPlayerInfo">
                                                <table class="matchUpStats">
                                                    <tr>
                                                        <th colspan="3" class="matchUpTeamName"> Team 1</th>
                                                    </tr>
                                                    <tr class="matchUpHeader">
                                                        <th>Name</th>
                                                        <th style="width: 20%;">Kills</th>
                                                        <th style="width: 20%;">Deaths</th>
                                                    </tr>
                                                    <?php foreach ($team1Pkmn as $p): ?>
                                                        <tr>
                                                            <td class="matchName"><?= htmlspecialchars($p['pokemon_name']) ?></td>
                                                            <td class="matchKill"><?= $p['kills'] ?></td>
                                                            <td class="matchDeath"><?= $p['deaths'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <tr>
                                                        <td colspan="3" class="matchResultCont">
                                                            <?= $match['winner_active_user_id'] == $match['player1_active_user_id'] ? 'Win' : 'Loss' ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </section>

                                            <section class="vsCont">VS</section>

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
                                                        <td colspan="3" class="matchResultCont">
                                                            <?= $match['winner_active_user_id'] == $match['player2_active_user_id'] ? 'Win' : 'Loss' ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </section>
                                        </section>
                                        <section class="replayCont">
                                            <?= htmlspecialchars($match['replay_link']) ?> 
                                        </section>
                                    </section>
                                <?php endforeach; ?>
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