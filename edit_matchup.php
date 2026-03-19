<?php
require_once 'includes/connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$seasonId = $_SESSION['season_id'] ?? null;
$matchupId = $_GET['matchup_id'] ?? null;

if (!$matchupId) {
    echo "Matchup not specified";
    exit;
}

// Fetch matchup info
$stmt = $conn->prepare("SELECT * FROM matchup WHERE id = ?");
$stmt->bind_param("i", $matchupId);
$stmt->execute();
$matchup = $stmt->get_result()->fetch_assoc();
if (!$matchup) exit("Matchup not found");

// Fetch Team 1 Pokémon
$stmt1 = $conn->prepare("
    SELECT mps.*, sd.name AS pokemon_name
    FROM match_pokemon_stats mps
    JOIN roster_pkmn rp ON mps.roster_pkmn_id = rp.id
    JOIN showdown_pkmn sd ON rp.showdown_pkmn = sd.id
    WHERE mps.matchup_id = ? AND rp.active_user = ?
");
$stmt1->bind_param("ii", $matchupId, $matchup['player1_active_user_id']);
$stmt1->execute();
$team1Pkmn = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch Team 2 Pokémon
$stmt2 = $conn->prepare("
    SELECT mps.*, sd.name AS pokemon_name
    FROM match_pokemon_stats mps
    JOIN roster_pkmn rp ON mps.roster_pkmn_id = rp.id
    JOIN showdown_pkmn sd ON rp.showdown_pkmn = sd.id
    WHERE mps.matchup_id = ? AND rp.active_user = ?
");
$stmt2->bind_param("ii", $matchupId, $matchup['player2_active_user_id']);
$stmt2->execute();
$team2Pkmn = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/styles/styles.css">
<title>Edit Matchup</title>
</head>
<body>
<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
        <header class="headerCont">
            <div class="seasonCont">
                <div class="seasonBtn">Season <?= htmlspecialchars($seasonId) ?></div>
            </div>
            <div class="pageNameCont">
                <div class="pageTitle"> Edit Matchup</div>
            </div>
        </header>

        <main>
            <form id="edit_matchup_form">
                <input type="hidden" name="matchup_id" value="<?= $matchupId ?>">

                <h3>Team 1 Pokémon</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Pokemon</th>
                            <th>Kills</th>
                            <th>Deaths</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($team1Pkmn as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['pokemon_name']) ?></td>
                            <td><input type="number" name="kills[<?= $p['roster_pkmn_id'] ?>]" value="<?= $p['kills'] ?>"></td>
                            <td><input type="number" name="deaths[<?= $p['roster_pkmn_id'] ?>]" value="<?= $p['deaths'] ?>"></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>Team 2 Pokémon</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Pokemon</th>
                            <th>Kills</th>
                            <th>Deaths</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($team2Pkmn as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['pokemon_name']) ?></td>
                            <td><input type="number" name="kills[<?= $p['roster_pkmn_id'] ?>]" value="<?= $p['kills'] ?>"></td>
                            <td><input type="number" name="deaths[<?= $p['roster_pkmn_id'] ?>]" value="<?= $p['deaths'] ?>"></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <label for="replayLink">Showdown Replay</label>
                <input type="text" id="replayLink" name="replay_link" value="<?= htmlspecialchars($matchup['replay_link']) ?>">

                <button type="submit">Save Changes</button>
            </form>
        </main>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
    // Initialize the edit matchup form script
    initEditMatchupForm();
</script>
</body>
</html>