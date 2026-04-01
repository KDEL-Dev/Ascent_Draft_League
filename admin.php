<?php
session_start();

if (!isset($_SESSION['user_id'])) 
    {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? 12;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Ascent - Admin Settings</title>
</head>
<body>
    <div class="pageLayout">

        <?php include 'includes/navbar.php';?>

        <div class="pageContent">
            <header class="headerCont">
                <div class="seasonCont">
                    <div class="seasonBtn">Season <?php echo $seasonId; ?></div>
                </div>
                <div class="pageNameCont">
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                    <div class="pageTitle"> Admin Settings</div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div class="pageLayout">
                <main class="centerMain">
                    <section class="adminContentCont">
                        <div class="adminSettingsCont">
                            <div class="adminSettingsHeader">League Initial Setup</div>
                            <div>
                                <button id="insertPokemonBtn" class="adminSettingsBtn">Update Pokemon Database</button>
                            </div>
                            <div>
                                <button id="insertPkmnTierBtn" class="adminSettingsBtn">Insert Current Pokémon Tiers</button>
                            </div>
                            <div>
                                <button id="clearDraftBtn" class="adminSettingsBtn">Reset Draft & Rosters</button>
                            </div>
                           
                        </div>
                        <div class="adminSettingsCont">
                            <div class="adminSettingsHeader">League Management</div>
                             <div>
                                <a href="/ascent_draft_league/edit_role.php" class="adminSettingsBtn">Users Management</a>
                            </div>
                            <div>
                                <p class="adminSectionTitle">Written Content Update</p>
                            </div>
                            <div>
                                <button class="adminSettingsBtn">Update Overview - News </button>
                            </div>
                            <div>
                                <button class="adminSettingsBtn" id="updateLeagueInfoBtn">
                                    <a href="edit_league_info.php">Update League Information </a>
                                </button>
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