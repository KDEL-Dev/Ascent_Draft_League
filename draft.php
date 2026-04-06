<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$teamName = htmlspecialchars($_SESSION['team_name']);
$seasonId = $_SESSION['season_id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ascent - Draft</title>
    <link rel="stylesheet" href="assets/styles/styles.css">
</head>
<body data-team-name="<?php echo $teamName; ?>">

<div class="pageLayout">

    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">

        <header class="headerCont">
            <div class="seasonCont">
                <div class="seasonBtn">Season <?php echo $seasonId; ?></div>
            </div>
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                <div class="pageTitle">Draft</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <main>
            <!-- ---------------- DRAFT DASHBOARD ---------------- -->
            <section id="draftCont">
                <header id="draftDashTitle">Draft Dashboard</header>

                <section id="draftInfoCont">
                    <section id="currentPick">
                        <header>Current Pick</header>
                        <section id="currentPickInfo">Stand By</section>
                    </section>

                    <section id="previousPick">
                        <header>Previous Pick</header>
                        <section id="previousPickInfo">
                            <section id="ppTeamName">
                                <!-- Dynamically Added -->
                            </section>
                            <section id="ppFlexRow">
                                <section id="ppPkmnCont">
                                    <section id="ppPkmnImgCont"></section>
                                    
                                </section>
                                <section id="ppStatCont">
                                    
                                        <table class="ppStatTable">
                                            <thead>
                                                <th class="ppTH">hp</th>
                                                <th class="ppTH">atk</th>
                                                <th class="ppTH">def</th>                                               
                                            </thead>
                                            <tbody>
                                                <td class="ppTD"></td>
                                                <td class="ppTD"></td>
                                                <td class="ppTD"></td>
                                               
                                            </tbody>
                                        </table>
                                  
                                
                                        <table class="ppStatTable">
                                            <thead>
                                                <th class="ppTH">sp.atk</th>
                                                <th class="ppTH">sp.def</th>
                                                <th class="ppTH">spe</th>
                                            </thead>
                                            <tbody>
                                             
                                                <td class="ppTD"></td>
                                                <td class="ppTD"></td>
                                                <td class="ppTD"></td>
                                            </tbody>
                                        </table>
                                
                                </section>
                            </section>
                            
                            <section id="ppPkmnNameCont"></section>
                        </section>
                    </section>

                    <section id="draftOrderCont">
                        <header>Draft Order</header>
                        <ul id="draftOrderList"></ul>
                        <section id="draftOrderBtnCont">
                            <button id="randomizeBtn">Randomize</button>
                        </section>
                    </section>
                    <section id="draftAdminBtnCont">
                        <button id="startDraftBtn" class="adminDraftBtns">Start Draft</button>
                        <!-- Turning off skip for now -->
                        <!-- <button id="skipPickBtn" class="adminDraftBtns" disabled>Skip</button> -->
                        <button id="endDraftBtn" class="adminDraftBtns">End Draft</button>
                    </section>
                </section>
                
            </section>

            <!-- ---------------- POKEMON TIERS ---------------- -->
            <section class="metaCont">
                <div class="ouPool">
                    <div class="tierPoolTitle" id="ouPoolTierColor"><h2>OU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfOuPkmn"></ul>
                </div>
            </section>

            <section class="metaCont">
                <div class="uuPool">
                    <div class="tierPoolTitle" id="uuPoolTierColor"><h2>UU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfUuPkmn"></ul>
                </div>
            </section>

            <section class="metaCont">
                <div class="ruPool">
                    <div class="tierPoolTitle" id="ruPoolTierColor"><h2>RU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfRuPkmn"></ul>
                </div>
            </section>

            <section class="metaCont">
                <div class="nuPool">
                    <div class="tierPoolTitle" id="nuPoolTierColor"><h2>NU Tier</h2></div>
                    <ul class="listOfMetaPkmn" id="listOfNuPkmn"></ul>
                </div>
            </section>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</div>

<!-- Load JS separately -->
<script src="assets/js/script.js"></script>
</body>
</html>