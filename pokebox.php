<?php
// pokebox.php
// Only needed if you want to fetch teams from DB for JS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'connection.php';

// Fetch teams for this active user (optional)
$activeUserId = 1; // hardcoded for now or get from session
$teamsQuery = $conn->prepare("SELECT * FROM rosterPkmn WHERE activeuser_id = ?");
$teamsQuery->bind_param("i", $activeUser_Id);
$teamsQuery->execute();
$teamsResult = $teamsQuery->get_result();

$teams = [];
while($row = $teamsResult->fetch_assoc()){
    $teams[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="styles/styles.css">
    

    <title>pokebox</title>
</head>
<body>
    <div class="pageLayout">
        <div class="navBar">
            <div class="topNav">
                <div>
                    <img class="navLogo" src="img/Ascent-White.svg" alt="site logo">
                </div>
                <ul class="navMainBtnCont">
                    <li class="navMainBtns"><a href="index.html">Overview</a></li>
                    <li class="navMainBtns"><a href="rosters.html">Roster</a></li>
                    <li class="navMainBtns"><a href="pokebox.html">Draft</a></li>
                    <li class="navMainBtns">Standings</li>
                    <li class="navMainBtns">Statistics</li>
                    <li class="navMainBtns">Matchups</li>
                    <li class="navMainBtns">Playoffs</li>
                    <li class="navMainBtns">League Information</li>
                    <li class="navMainBtns">Draft Recap</li>
                    
                </ul>
            </div>
            <div class="navSettingsCont">
                <div class="profileSettingsBtn">Profile</div>
                <div class="adminSettingsBtn"><a href="admin.html">Admin Settings</a></div>
            </div>
        </div>
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
                    <section id="metaCont">
                        <div class="ouPool">
                            <div class="tierPoolTitle" id="ouPoolTierColor">
                                <h2>OU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfOuPkmn"></ul>
                        </div>
                    </section>
                    <section id="metaCont">
                        <div class="uuPool">
                            <div class="tierPoolTitle" id="uuPoolTierColor">
                                <h2>UU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfUuPkmn"></ul>
                        </div>
                    </section>
                    <section id="metaCont">
                        <div class="ruPool">
                            <div class="tierPoolTitle" id="ruPoolTierColor">
                                <h2>RU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfRuPkmn"></ul>
                        </div>
                    </section>
                    <section id="metaCont">
                        <div class="nuPool">
                            <div class="tierPoolTitle" id="nuPoolTierColor">
                                <h2>NU Tier</h2>
                            </div>
                            <ul class="listOfMetaPkmn" id="listOfNuPkmn"></ul>
                        </div>
                    </section>
                </main>
            </div>
            <footer>
                Copyright Ascent Draft League 2026
            </footer>
        </div>
    </div>
</body>


<script>
  // Pass PHP teams to JS
  let teams = <?php echo json_encode($teams); ?>;
</script>


<script src="script.js"></script>
</html>