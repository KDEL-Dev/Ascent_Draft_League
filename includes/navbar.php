<?php
    if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
?>

        <div class="navBar">
            <div class="topNav">
                <div>
                    <a href="index.php"><img class="navLogo" src="img/Ascent-White.svg" alt="site logo"></a>
                </div>
                <ul class="navMainBtnCont">
                    <li class="navMainBtns">
                        <a href="index.php">
                            <img src="img/icons/Grid.svg" class="navMainIcon" alt="Overview icon">Overview
                        </a>
                    </li>
                    <li class="navMainBtns">
                        <a href="rosters.php">
                            <img src="img/icons/PokeBall_Icon.svg" class="navMainIcon" alt="pokeball ball icon"> Roster
                        </a>
                    </li>
                    <li class="navMainBtns"><a href="draft.php">Draft</a></li>
                    <li class="navMainBtns">
                        <img src="img\icons\standingsIcon.svg" class="navMainIcon" alt="standings icon">
                        Standings
                    </li>
                    <li class="navMainBtns">Statistics</li>
                    <li class="navMainBtns"><a href="matchup.php">Matchups</a></li>
                    <!-- <li class="navMainBtns">Playoffs</li> -->
                    <li class="navMainBtns"><a href="league_information.php">League Information</a></li>
                    <li class="navMainBtns">
                        <a href="draft_recap.php">
                            <img src="img/icons/File text.svg" class="navMainIcon" alt="Draft Recap icon">Draft Recap
                        </a>
                    </li>   
                </ul>
            </div>
            <div class="navSettingsCont">
                <div id="profileName">Welcome <?= $_SESSION['gamerTag'] ?? 'Spectator'; ?></div>
                <div class="profileSettingsBtn">
                    <a href="profile.php">
                        <img src="img/icons/User.svg" alt="profile icon">Profile
                    </a>
                </div>
                <div class="adminSettingsBtn"><a href="admin.php">Admin Settings</a></div>
            </div>
        </div>