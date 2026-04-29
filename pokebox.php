<?php
    session_start();
    require_once 'includes/connection.php';

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
    <link rel="icon" type="image/png" sizes="32x32" href="img/Ascent-White.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/Ascent-White.png">
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
                <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
                <div class="pageTitle">Pokemon Swap</div>
                <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
            </div>
        </header>

        <div>
            <main>

                <!-- OU -->
                <section class="metaCont">
                    <div class="tierPoolTitle" id="ouPoolTierColor"><h2>OU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfOuPkmn"></ul>
                </section>

                <!-- UU -->
                <section class="metaCont">
                    <div class="tierPoolTitle" id="uuPoolTierColor"><h2>UU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfUuPkmn"></ul>
                </section>

                <!-- RU -->
                <section class="metaCont">
                    <div class="tierPoolTitle" id="ruPoolTierColor"><h2>RU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfRuPkmn"></ul>
                </section>

                <!-- NU -->
                <section class="metaCont">
                    <div class="tierPoolTitle" id="nuPoolTierColor"><h2>NU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfNuPkmn"></ul>
                </section>

            </main>

            <?php include 'includes/footer.php'; ?>
        </div>

    </div>

</div>
</body>
</html>