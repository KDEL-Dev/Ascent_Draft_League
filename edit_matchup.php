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
<button id="hamburgerBtn">☰</button>
<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
        <header class="headerCont">

            <?php include 'includes/season_setting_header.php';?>

            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                <div class="pageTitle"> Edit Match</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <main>
            <section class="contentCont">

            
                <form id="edit_matchup_form">
                    <input type="hidden" name="matchup_id" value="<?= $matchupId ?>">
                    <section class="editMatchRow">
                        <section class="editMatchCol">
                            <h3>Team 1 Pokémon</h3>
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
                                <h3>Team 2 Pokémon</h3>
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
                                <input type="radio" name="winner" value="team1"> Team 1
                            </label>
                            <label>
                                <input type="radio" name="winner" value="team2"> Team 2
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
        </main>
    </div>
</div>

<script>
const matchupId = <?= json_encode($matchupId) ?>;
</script>
<script src="assets/js/script.js"></script>

</body>
</html>