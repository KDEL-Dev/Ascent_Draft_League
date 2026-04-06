<?php



session_start();

if (!isset($_SESSION['user_id'])) 
    {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>
    <title>Ascent - Add Match</title>
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
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                    <div class="pageTitle"> Add New Match</div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">
                        <section class="pageLayout">
                            <section class="teamSelectCont">
                                <p><b>FIRST</b>, select the two teams that took part in the match and press "Load Teams".</p>
                                <select id="teamOneSelect">
                                    <option value="">Select Team 1</option>
                                </select>
                                
                                <select id="teamTwoSelect">
                                    <option value="">Select Team 2</option>
                                </select>
                                <button id="loadSelectedTeamsBtn">Load Teams</button>


                                <p>Select the Pokemon that participated in the battle.</p>
                                <section id="selectMatchPkmn">
                                    <h3 id="team1Title">Team 1 Pokémon</h3>
                                    <ul class="selectMatchPkmn" id="team1Container"></ul>

                                    <h3 id="team2Title">Team 2 Pokémon</h3>
                                    <ul class="selectMatchPkmn" id="team2Container"></ul>
                                </section>  
                            </section>

                            <form id="add_matchup_form">
                                <p>Record the stats from the match here.</p>
                                <h3 id="team1Count">Team 1 (0 / 6 selected)</h3>
                                <table class="newMatchTable">
                                    <thead>
                                        <tr>
                                            <th>Pokemon</th>
                                            <th>Kills</th>
                                            <th>Deaths</th>
                                        </tr>
                                    </thead>
                                    <tbody id="team1MatchTable"></tbody>
                                </table>

                                <h3 id="team2Count">Team 2 (0 / 6 selected)</h3>
                                <table class="newMatchTable">
                                    <thead>
                                        <tr>
                                            <th>Pokemon</th>
                                            <th>Kills</th>
                                            <th>Deaths</th>
                                        </tr>
                                    </thead>
                                    <tbody id="team2MatchTable"></tbody>
                                </table>   
                                <section>
                                    <h3>Select Winner</h3>
                                    <label id="winnerLabel1">
                                        <input type="radio" name="winner" value="team1" id="winnerTeam1">
                                        Team 1 Wins
                                    </label>
                                    <label id="winnerLabel2">
                                        <input type="radio" name="winner" value="team2" id="winnerTeam2">
                                        Team 2 Wins
                                    </label>
                                </section>
                                <section id="newMatchReplayCont">
                                    <label for=""> Showdown Replay </label>
                                    <input type="text" id="replayLink" placeholder="Add Replay Link">
                                </section>
                                <button type="submit" id="saveNewMatchBtn">Save Matchup</button>
                            </form>
                        </section>
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>