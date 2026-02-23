<?php
session_start();

if (!isset($_SESSION['user_id'])) 
    {
        header("Location: login.html");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Pokebox</title>
</head>
<body>
    <div class="pageLayout">
        
        <?php include 'includes/navbar.php';?>

        <div class="pageContent">
            <header class="headerCont">
                <div class="seasonCont">
                    <div class="seasonBtn">Season 1</div>
                </div>
                <div class="pageNameCont">
                    <div class="pageTitle"> Overview</div>
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
                        <article class="playerDashContent"> No new updates for this league</article>
                    </section>
                </main>
                <aside>
                    <!-- <section id="replayCont">
                        <div id="sectionTitle">Standings</div>
                        <div id="killLeader">Will update when league starts</div>
                    </section> -->
                    <section id="miniStandingsCont">
                        <div id="sectionTitle">Standings</div>
                        <div id="miniStandings">Will update when league starts</div>
                    </section>
                    <section>
                        <div id="killLeaderBoardCont">
                            <div id="sectionTitle">Kill Leader</div>
                            <div id="killLeader">Will update when league starts</div>
                        </div>
                    </section>
                </aside>
            </div>

            <?php include 'includes/footer.php'; ?>
            
        </div>
    </div>
    
</body>
</html>