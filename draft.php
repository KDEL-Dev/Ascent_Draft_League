<?php
    session_start();
    require_once 'includes/connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;

    $draftFinished = 0;

    if ($seasonId) 
    {
        $stmt = $conn->prepare("
            SELECT draft_finished 
            FROM draft_info 
            WHERE season_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $seasonId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($result) 
        {
            $draftFinished = (int)$result['draft_finished'];
        }
    }

    if ($draftFinished == 1) 
    {
        header("Location: pokebox.php");
        exit;
    }

    $teamName = htmlspecialchars($_SESSION['team_name']);
    
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ascent - Draft</title>
    <link rel="stylesheet" href="assets/styles/styles.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/Ascent-White.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/Ascent-White.png">
</head>
<body data-team-name="<?php echo $teamName; ?>">

<button id="hamburgerBtn">☰</button>

<div class="pageLayout">

    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">

        <header class="headerCont">
            
            <?php include 'includes/season_setting_header.php';?>
            
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon-color-filled.svg" alt="pokeball icon">
                <div class="pageTitle">Draft</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <main>

            <section class="draftCont">
                <!-- DRAFT ORDER -->
                <section id="draftOrderCont">
                    <header>Draft Order</header>
                    <ul id="draftOrderList"></ul>
                    <section>
                        <?php if (in_array($_SESSION['role'], ['admin', 'owner'])): ?>
                            <section id="draftOrderBtnCont">
                                <button id="randomizeBtn">Randomize</button>
                            </section>
                        <?php endif; ?>
                    </section>
                </section>
                <section>
                    <?php if (in_array($_SESSION['role'], ['admin', 'owner'])): ?>
                        <section id="draftAdminBtnCont">
                            <button id="startDraftBtn" class="adminDraftBtns">Start Draft</button>
                            <button id="endDraftBtn" class="adminDraftBtns">End Draft</button>
                        </section>
                    <?php endif; ?>
                </section>
            </section>

            <section class="draftTogglesCont">
                <section>
                    <button id="draftMainDisplay">Draft Team View</button>
                    <button id="draftDisplayAllBtn">Display All Tiers</button> 
                </section>
                <section>
                    <button id="displayOuBtn">Display OU </button>
                    <button id="displayUuBtn">Display UU </button>
                    <button id="displayRuBtn">Display RU </button>
                    <button id="displayNuBtn">Display NU </button>
                </section>
            </section>

            <section class="draftPickCont">
                <section id="previousPickWidth">
                    <!-- PREVIOUS DRAFT PICK -->
                    <section id="previousPick">
                        <header>Drafted</header>
                        <section id="previousPickInfo">
                            <section id="ppTeamName"></section>
                            <section id="ppPkmnCont">
                                <section id="ppPkmnImgCont">
                                </section> 
                                <section>
                                    <h2 id="ppPkmnTierBg"></h2>
                                </section>            
                            </section>
                            <section class="ppFlexCol">
                                <section id="ppPkmnNameCont"></section>
                                <section id="ppPkmnType">
                                    <!-- Dynamically created -->
                                </section>
                                <section id="ppStatCont"></section>
                            </section>
                        </section>
                    </section>
                
            

                    <!-- CURRENT DRAFT PICK AND DRAFT INFO -->
                    <section id="draftConsoleSticky">
                        <section id="currentPick">
                            <header>Current Pick</header>
                            <section id="currentPickInfo">Stand By</section>
                        </section>
                        <section id="yourDraftCont">
                            <section>
                                <header id="yourDraftTeamName">
                                    Your Draft Information
                                    <!-- Switch this to team name -->
                                </header>
                            </section>
                            <section id="yourDraftInfo">
                                <section id="yourDraftCountCont">
                                    <section>
                                        <p>Roster Counter</p>
                                    </section>
                                    <section>
                                        <h2><span id="yourTeamCount">0</span> / 12</h2>
                                    </section>
                                </section>
                                <section id="yourDraft">
                                    <div class="yourPicks" id="yourOu">
                                        <p class="rosterTierText" data-tier="ou">OU / UUBL</p>
                                        <p class="rosterTierText" data-tier="ou">OU / UUBL</p>
                                        <p class="rosterTierText" data-tier="ou">OU / UUBL</p>
                
                                    </div>
                                    <div class="yourPicks" id="yourUu">
                                        <p class="rosterTierText" data-tier="uu">UU / RUBL</p>
                                        <p class="rosterTierText" data-tier="uu">UU / RUBL</p>
                                        <p class="rosterTierText" data-tier="uu">UU / RUBL</p>
                                    </div>
                                    <div class="yourPicks" id="yourRu">
                                        <p class="rosterTierText" data-tier="ru">RU / NUBL</p>
                                        <p class="rosterTierText" data-tier="ru">RU / NUBL</p>
                                        <p class="rosterTierText" data-tier="ru">RU / NUBL</p>
                                    </div>
                                    <div class="yourPicks" id="yourNu">
                                        <p class="rosterTierText" data-tier="nu">NU / BELOW</p>
                                        <p class="rosterTierText" data-tier="nu">NU / BELOW</p>
                                        <p class="rosterTierText" data-tier="nu">NU / BELOW</p>
                                    </div>
                                </section>
                            </section>
                        </section>
                    </section>
                </section>
              
            </section>

            <!-- ---------------- DRAFT DASHBOARD ---------------- -->
            <!-- <section id="draftCont">
                <header id="draftDashTitle">Draft Dashboard</header>

                <section id="draftInfoCont">
                    <section id="draftInfoTopCont">
                        <section id="currentPick">
                            <header>Current Pick</header>
                            <section id="currentPickInfo">Stand By</section>
                        </section>
                        <section id="previousPick">
                            <header>Previous Pick</header>
                            <section id="previousPickInfo">
                                <section id="ppTeamName">
                                </section>
                                <section id="ppFlexRow">
                                    <section id="ppPkmnCont">
                                        <section id="ppPkmnImgCont"></section>
                                        
                                        </section>
                                    <section id="ppStatCont">
                                    </section>
                                </section>
                                
                                <section id="ppPkmnNameCont"></section>
                            </section>
                        </section>

                        <section id="draftOrderCont">
                            <header>Draft Order</header>
                            <ul id="draftOrderList"></ul>
                    
                        <?php if (in_array($_SESSION['role'], ['admin', 'owner'])): ?>
                            <section id="draftOrderBtnCont">
                                <button id="randomizeBtn">Randomize</button>
                            </section>
                        <?php endif; ?>
                    </section>
                </section>
                    <?php if (in_array($_SESSION['role'], ['admin', 'owner'])): ?>
                        <section id="draftAdminBtnCont">
                            <button id="startDraftBtn" class="adminDraftBtns">Start Draft</button>
                            <button id="endDraftBtn" class="adminDraftBtns">End Draft</button>
                        </section>
                    <?php endif; ?>
                </section>
                
            </section> -->

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