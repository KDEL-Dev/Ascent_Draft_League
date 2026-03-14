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

    <title>Admin Settings</title>
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
                    <div class="pageTitle"> Admin Settings</div>
                </div>
            </header>
            <div class="pageLayout">
                <main>
                    <section class="contentCont">
                        <div class="adminSettingsCont">
                            <div class="adminSettingsHeader">League Setup</div>
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
                                <button class="adminSettingsBtn">Update League News </button>
                            </div>
                            <div>
                                <button class="adminSettingsBtn" id="updateLeagueInfoBtn">Update League Information </button>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
            
        </div>
    </div>

    <!--------------- 
        MODALS 
    ---------------->

    <div id="editLeagueInfoModal" class="modal hidden">

    <div class="modalContent">

        <div class="modalHeader">
            <h2>Edit League Information</h2>
            <button id="closeModalBtn">X</button>
        </div>

        <form id="leagueInfoForm">

            <h3>Important Dates</h3>

            <label>Draft Date</label>
            <input type="date" name="draft_date" id="draftDate">

            <label>Season Start</label>
            <input type="date" name="season_start" id="seasonStart">

            <h3>Rules</h3>

            <div id="rulesContainer"></div>

            

            <button type="button" id="addRuleBtn">Add Rule</button>

            <br><br>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>


</body>
</html>