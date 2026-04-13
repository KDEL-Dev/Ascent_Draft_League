<?php
    if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
?>

<div class="seasonCont">  
    
    <div class="seasonBtn">Season <?php echo htmlspecialchars($seasonId); ?></div>
     
    <section id="profileAndSettingCont">
        <a href="profile.php">
            <div id="topProfileSettingBtn">
                <img src="img/icons/icons8-profile-64.png"  alt="admin button">
                <?php
                    $teamName = $_SESSION['team_name'] ?? 'Spectator';
                ?>
                <p><?= $teamName ?></p>
            </div>
            <div>
                
            </div>
        </a> 
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'owner')): ?>
            <a href="admin.php">
                <div id="topAdminSettingBtn">
                    <img src="img/icons/icons8-settings.svg"  alt="admin button">
                </div>
            </a>
        <?php endif; ?>
    </section>                    
</div>