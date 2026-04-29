<?php
session_start();

if (!isset($_SESSION['user_id'])) 
    {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;
    $matchupId = $_GET['matchup_id'] ?? null;
    if (!$matchupId) {
        echo "Matchup not specified";
        exit;
    }

    require_once 'includes/connection.php';

    $sql = "
    SELECT 
        m.*,
        u1.team_name AS team1_name,
        u2.team_name AS team2_name
    FROM matchup m
    JOIN active_users au1 ON au1.id = m.player1_active_user_id
    JOIN users u1 ON u1.id = au1.user_id

    JOIN active_users au2 ON au2.id = m.player2_active_user_id
    JOIN users u2 ON u2.id = au2.user_id

    WHERE m.id = ? AND m.season_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $matchupId, $seasonId);
    $stmt->execute();

    $matchup = $stmt->get_result()->fetch_assoc();

    if (!$matchup) 
    {
        die("Matchup not found");
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
<title>Edit Matchup</title>
</head>
<body>
<button id="hamburgerBtn">☰</button>
<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
        <header class="headerCont">

            <?php include 'includes/season_setting_header.php';?>

            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
                <div class="pageTitle"> Edit Match</div>
                <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
            </div>
        </header>

        <main>
            <section class="contentCont">
                <section id="editMatchFormCont">
            
                    <form id="edit_matchup_form">
                        <input type="hidden" name="matchup_id" value="<?= $matchupId ?>">
                        <section class="editMatchRow">
                            <section class="editMatchCol">
                                <h3><?= htmlspecialchars($matchup['team1_name']) ?></h3>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Pokemon</th>
                                            <th>Kills</th>
                                            <th>Deaths</th>
                                        </tr>
                                    </thead>
                                    <tbody id="team1Body"></tbody>
                                </table>
                            </section>
                            <section class="editMatchRow">
                                <section class="editMatchCol">
                                    <h3><?= htmlspecialchars($matchup['team2_name']) ?></h3>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Pokemon</th>
                                                <th>Kills</th>
                                                <th>Deaths</th>
                                            </tr>
                                        </thead>
                                        <tbody id="team2Body"></tbody>
                                    </table>
                                </section>
                            </section>
                        </section>
                        <section class="editMatchRow">
                            <section class="editMatchCol">
                                <h3>Winner</h3>

                                <label>
                                    <input type="radio" name="winner" value="team1">     <?= htmlspecialchars($matchup['team1_name']) ?>

                                </label>
                                <label>
                                    <input type="radio" name="winner" value="team2"> <?= htmlspecialchars($matchup['team2_name']) ?>
                                </label>
                            </section>
                        </section>
                        <section class="editMatchRow" id="editMatchReplaySect">
                                <section class="editMatchCol">
                                    <label>Replay</label>
                                    <input type="text" name="replay_link" id="replayLink">
                                </section>
                        </section>
                        <section class="editMatchRow">
                                <section class="editMatchCol">
                                    <button type="submit" id="editMatchSaveBtn">Save Changes</button>
                                </section>
                            </section>
                        </section>    
                    </form>
                </section>
            </section>
        </main>
    </div>
</div>

<script>
const matchupId = <?= json_encode($matchupId) ?>;
</script>
<script src="assets/js/script.js"></script>

</body>
</html>