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
    <script src="/ascent_draft_league/assets/js/script.js"></script>

    <title>League Information</title>
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
                        <section id="leagueInfoCont">
                            <section id="leagueInfoRow">
                                <section id="whatIsCont" class="leagueInfoSect">
                                    <h2>What is Ascent Draft League?</h2>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Porro, libero laborum! Facilis, recusandae. Porro, at similique libero id maxime nesciunt ullam cumque incidunt consectetur? Placeat enim laboriosam pariatur assumenda nam!</p>
                                </section>
                                <section id="importantDatesCont" class="leagueInfoSect">
                                    <h2>Important Dates:</h2>
                                    <h3>Draft Date:</h3>
                                    <p>Date goes here</p>
                                    <h3>Season Start:</h3>
                                    <p>Date Goes Here</p>
                            

                                </section>
                            </section>
                            <section id="formatRulesCont" class="leagueInfoSect">
                                <h2>Format and Rules</h2>
                                <ul id="ruleList">
                                    <!-- Adding list from db -->
                                </ul>
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