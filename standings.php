<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
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

    <title>Ascent - Standings</title>
</head>
<body>
    <button id="hamburgerBtn">☰</button>
<div class="pageLayout">

    <?php include 'includes/navbar.php';?> 

    <div class="pageContent">

        <header class="headerCont">

            <?php include 'includes/season_setting_header.php';?>
            
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                <div class="pageTitle">Standings</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <div>
            <main class="centerMain">
                <section class="shortContentCont">
                    <section id="standingsCont">
                        <table id="standingsTable">
                            <thead id="standingsHeader">
                                <tr>
                                    <th style="width: 15%;">Rank</th>
                                    <th style="width: 40%;">Teams</th>
                                    <th style="width: 15%;">Wins</th>
                                    <th style="width: 15%;">Losses</th>
                                    <!-- <th style="width: 15%;">+/-</th> -->
                                </tr>
                            </thead>
                            <tbody id="standingsBody">
                                <!-- Dynamically Added -->
                            </tbody>
                        </table>
                    </section>
                </section>
            </main>

            <?php include 'includes/footer.php'; ?>
        </div>

    </div>

</div>
</body>
</html>