<!-- Must have this at the start of every page -->
<?php

session_start();

require_once 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    

    <title>pokebox</title>
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
                    <div class="pageTitle"> Draft</div>
                </div>
            </header>
            <div class="pageLayout">
                <main>
                    <section id="draftCont">
                        <header id="draftDashTitle">Draft Dashboard</header>
                        <section id="draftInfoCont">    
                            <section id="currentPick">
                                <header>Current Pick</header>
                                <section id="currentPickInfo">
                                    Player 1
                                </section>
                            </section>
                            <section id="previousPick">
                                <header>Previous Pick</header>
                                <section id="previousPickInfo">

                                </section>
                            </section>
                            <section id="draftOrderCont">
                                
                                    <header>Draft Order</header>
                                        <ul id="draftOrderList">
                                        <!-- Content will come from javascript/php --> 
                                        </ul>
                                        <section id="draftOrderBtnCont">
                                            <!-- If I need an edit button just uncomment but normally I only randomize picks -->
                                            <!-- <button id="">Edit</button> -->
                                            <button id="randomizeBtn">Randomize</button>
                                        </section>
                            </section>
                        
                        </section>
                    </section>

                    <section class="metaCont">
                        <div class="ouPool">
                            <div class="tierPoolTitle" id="ouPoolTierColor">
                                <h2>OU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfOuPkmn"></ul>
                        </div>
                    </section>

                    <section class="metaCont">
                        <div class="uuPool">
                            <div class="tierPoolTitle" id="uuPoolTierColor">
                                <h2>UU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfUuPkmn"></ul>
                        </div>
                    </section>
                    <section class="metaCont">
                        <div class="ruPool">
                            <div class="tierPoolTitle" id="ruPoolTierColor">
                                <h2>RU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfRuPkmn"></ul>
                        </div>
                    </section>
                    <section class="metaCont">
                        <div class="nuPool">
                            <div class="tierPoolTitle" id="nuPoolTierColor">
                                <h2>NU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfNuPkmn"></ul>
                        </div>
                    </section>
                </main>
            </div>

            <?php include 'includes/footer.php'; ?>
            
        </div>
    </div>
</body>





<script src="assets/js/script.js"></script>
</html>