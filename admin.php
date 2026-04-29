<?php
session_start();

    if (!isset($_SESSION['user_id'])) 
    {
        header("Location: login.php");
        exit;
    }

    if (!isset($_SESSION['user_id']) || 
    !in_array($_SESSION['role'], ['admin', 'owner'])) 
    {
        header("Location: index.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? 0;
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

    <title>Ascent - Admin Settings</title>
</head>
<body>
    <div class="pageLayout">

        <?php include 'includes/navbar.php';?>

        <div class="pageContent">
            <header class="headerCont">

                <?php include 'includes/season_setting_header.php';?>
                
                <div class="pageNameCont">
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                    <div class="pageTitle"> Admin Settings</div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div class="pageLayout">
                <main class="centerMain">
                    <section class="adminContentCont">
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
                            <div class="adminSettingsCont">
                                <div class="adminSettingsHeader" >Database</div>
                                <div>
                                    <button id="insertPokemonBtn" class="adminSettingsBtn">Update Pokemon Database</button>
                                </div>
                                <div>
                                    <button id="insertPkmnTierBtn" class="adminSettingsBtn">Insert Current Pokémon Tiers</button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="adminSettingsCont">
                            <div class="adminSettingsHeader">Pre-Draft Resets</div>
                            <div>
                                <button id="clearMatchupBtn" class="adminSettingsBtn">Clear All Matchups</button>
                            </div>
                            <div>
                                <button id="clearDraftBtn" class="adminSettingsBtn">Reset Draft & Rosters</button>
                            </div>
                        </div>

                        <div class="adminSettingsCont">
                            <div class="adminSettingsHeader">League Management</div>
                             <div>
                                <a href="edit_role.php" class="adminSettingsBtn">Users Management</a>
                            </div>
                            <div>
                                <p class="adminSectionTitle">Ovewview/League Information</p>
                            </div>
                            <div>
                                <button class="adminSettingsBtn">
                                    <a href="edit_news.php">Update News </a>
                                </button>
                            </div>
                            <div>
                                <button class="adminSettingsBtn" id="updateLeagueInfoBtn">
                                    <a href="edit_league_info.php">Update - League Information </a>
                                </button>
                            </div>
                            
                        </div>
                        <div class="adminSettingsCont">
                            <div class="adminSettingsHeader">Transactions</div>
                                <div>
                                    <button id="toggleSwapsBtn" class="adminSettingsBtn" >Toggle Swaps</button>
                                </div>
                            <div>
                                <a href="edit_swaps.php" class="adminSettingsBtn">
                                    Reset Swap Count
                                </a>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
            
        </div>
    </div>
    
</div>


</body>
</html>