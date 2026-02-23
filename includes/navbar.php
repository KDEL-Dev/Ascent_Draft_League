<?php
    if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
?>

        <div class="navBar">
            <div class="topNav">
                <div>
                    <img class="navLogo" src="img/Ascent-White.svg" alt="site logo">
                </div>
                <ul class="navMainBtnCont">
                    <li class="navMainBtns"><a href="index.php">Overview</a></li>
                    <li class="navMainBtns"><a href="rosters.php">Roster</a></li>
                    <li class="navMainBtns"><a href="pokebox.php">Draft</a></li>
                    <li class="navMainBtns">Standings</li>
                    <li class="navMainBtns">Statistics</li>
                    <li class="navMainBtns">Matchups</li>
                    <li class="navMainBtns">Playoffs</li>
                    <li class="navMainBtns">League Information</li>
                    <li class="navMainBtns">Draft Recap</li>   
                </ul>
            </div>
            <div class="navSettingsCont">
                <div id="profileName">Welcome <?= $_SESSION['gamerTag'] ?? 'Spectator'; ?></div>
                <div class="profileSettingsBtn"><a href="profile.php">Profile</a></div>
                <div class="adminSettingsBtn"><a href="admin.html">Admin Settings</a></div>
            </div>
        </div>