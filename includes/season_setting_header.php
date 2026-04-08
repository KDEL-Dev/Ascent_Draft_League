<?php
    if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
?>

<div class="seasonCont">  
    
    <div class="seasonBtn">Season <?php echo htmlspecialchars($seasonId); ?></div>
    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'owner')): ?>
        <a href="admin.php">
            <div class="adminSettingIcon">
                <img src="img/icons/icons8-settings.svg"  alt="admin button">
            </div>
        </a>
    <?php endif; ?>               
    <a href="profile.php">
        <div class="adminSettingIcon">
            <img src="img/icons/icons8-profile-64.png"  alt="admin button">
        </div>
    </a>                 
</div>