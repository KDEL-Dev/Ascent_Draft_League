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
                                <button>Add New Matchup</button>
                            </section>
                            <section id="matchupResultCont">
                                <section class="editDeleteMatchCont">
                                    <button>edit</button>
                                    <button>delete</button>
                                    <section class="matchBox">
                                        <section class="matchUpPlayerInfo">
                                                <table class="matchUpStats">
                                                    <tr>
                                                        <th colspan="3" class="matchUpTeamName"> Team 1</th>
                                                    </tr>
                                                    <tr class="matchUpHeader">
                                                        <th>Name</th>
                                                        <th style="width: 20%;">Kill</th>
                                                        <th style="width: 20%;">Deaths</th>
                                                    </tr>
                                                    <tr class="matchUpInfo">
                                                        <td class="matchName">Tauros-Paldea-Blaze</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="matchResultCont"> Win</td>
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
                                                        <th style="width: 20%;">Kill</th>
                                                        <th style="width: 20%;">Deaths</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="matchName">pkmn</td>
                                                        <td class="matchKill">0</td>
                                                        <td class="matchDeath">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="matchResultCont"> Loss</td>
                                                    </tr>
                                                </table>
                                        </section>
                                    </section>
                                    
                                    <section class="replayCont">Replay</section>
                                    
                                </section>
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