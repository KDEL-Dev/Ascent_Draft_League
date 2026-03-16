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
    <title>Document</title>
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
                    <div class="pageTitle"> League Information</div>
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">

                        <section class="teamSelectCont">
                            <select id="teamOneSelect">
                                <option value="">Select Team 1</option>
                            </select>
                            
                            <select id="teamTwoSelect">
                                <option value="">Select Team 2</option>
                            </select>
                            

                            <button id="loadSelectedTeamsBtn">Load Teams</button>
                        
                            <div class="teamPkmnCont">
                                <h3>Team 1 Pokémon</h3>
                                <ul id="team1Container"></ul>
                            </div>

                            <div class="teamPkmnCont">
                                <h3>Team 2 Pokémon</h3>
                                <ul id="team2Container"></ul>
                            </div>
                        
                        
                        </section>

                        <form id="add_matchup_form">
                            <h3 id="team1Count">Team 1 (0 / 6 selected)</h3>
                            <table>
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
                            <table>
                                <thead>
                                    <tr>
                                        <th>Pokemon</th>
                                        <th>Kills</th>
                                        <th>Deaths</th>
                                    </tr>
                                </thead>
                                <tbody id="team2MatchTable"></tbody>
                            </table>   
                            
                            <button type="submit">Save Matchup</button>
                        </form>
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>