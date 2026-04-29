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
                <div class="pageTitle">PokeBox</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <section>
            <main class="centerMain">
                <section class="shortContentCont">
                    <section id="swapLockedMsg">
                        <p>Swaps are not permitted at this time</p>
                    </section>
                    <section id="swapTransactionsCont">
                        <p>Transactions Remaining: <span id="movesRemaining"></span></p>
                    </section>
                    <section class="swapPkmnCol">
                        <label>Selected Pokemon:</label>
                        <input id="availablePkmnName" type="text" disabled>
                        <input id="availablePkmnId" type="hidden">
                    </section>

                    

                    <section class="swapPkmnCol">
                        <label>Select Pokemon from your team to replace:</label>
                        <select id="dropSelect"></select>
                    </section>

                    <button id="confirmSwapBtn">Confirm Swap</button>
                </section>
            </main>

            <?php include 'includes/footer.php'; ?>
        </section>

    </div>

</div>

    
</body>
</html>