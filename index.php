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

    <title>Ascent - Overview</title>
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
                    <div class="pageTitle"> Overview</div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div class="pageLayout">
                <main>
                    <section class="newsCont">
                        <div class="sectionTitle">News/Updates</div>
                        <article class="newsContent"> No new updates for this league</article>
                    </section>
                    <section class="playerDashCont">
                        <div class="sectionTitle">Player Dashboard</div>
                        <article class="playerDashContent"> 
                            <section id="homeRosterCont">
                                <section id="homeRosterHeader">Team Name</section>
                                <section id="homeRosterBox">
                                    <ul id="homePkmnList">
                                        <!-- Javascript will load results -->
                                    </ul>
                                </section>
                            </section>
                        </article>
                    </section>
                </main>
                <aside>
                    <section id="replayCont">
                        <div class="smallerSectionTitle">Replay</div>
                        <div id="miniStandings">Will update after first match</div>
                    </section>
                    <section id="miniStandingsCont">
                        <div class="smallerSectionTitle">Standings</div>
                        <div id="miniStandings">Will update when league starts</div>
                    </section>
                    <section id="killLeaderBoardCont">
                            <div class="smallerSectionTitle">Kill Leader</div>
                            <div id="killLeader">Will update when league starts</div>             
                    </section>
                </aside>
            </div>

            <?php include 'includes/footer.php'; ?>
            
        </div>
    </div>
    
</body>
</html>