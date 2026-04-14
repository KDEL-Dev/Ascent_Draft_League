<?php
    if (session_status() === PHP_SESSION_NONE)
    {
        session_start();
    }

    require_once 'includes/connection.php';

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

        if ($result) {
            $draftFinished = (int)$result['draft_finished'];
        }
    }
?>

        <div class="navBar">
            <div class="topNav">
                <button id="hamburgerBtn">☰</button>
                <div>
                    <a href="index.php"><img class="navLogo" src="img\Ascent White Full Text.svg" alt="site logo"></a>
                </div>
                <ul class="navMainBtnCont">
                    <li class="navMainBtns">
                        <a href="index.php">
                            <img src="img/icons/Grid.svg" class="navMainIcon" alt="Overview icon">Overview
                        </a>
                    </li>
                    
                    
                    <?php if(!$draftFinished): ?> 
                        <li class="navMainBtns">
                            <a href="draft.php">
                                <img src="img\icons\Edit.svg" class="navMainIcon" alt="draft icon">Draft
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($draftFinished): ?>
                        <li class="navMainBtns">
                            <a href="pokebox.php">
                                <img src="img/icons/Edit.svg" class="navMainIcon" alt="pokebox icon">Pokebox
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="navMainBtns">
                        <a href="matchup.php">
                            <img src="img\icons\icons8-battle-50.png" alt="">Matchups
                        </a>
                    </li>
                    <li class="navMainBtns">
                        <a href="rosters.php">
                            <img src="img/icons/PokeBall_Icon.svg" class="navMainIcon" alt="pokeball ball icon"> Roster
                        </a>
                    </li>
                    <li class="navMainBtns">
                        <a href="standings.php">
                            <img src="img/icons/standingsIcon.svg" class="navMainIcon" alt="standings icon">
                            Standings
                        </a>
                    </li>
                    <li class="navMainBtns">
                        <a href="statistics.php">
                            <img src="img/icons/Bar_chart-2.svg" class="navMainIcon" alt="statistics icon"> Statistics
                        </a>
                    </li>
                    
                    <!-- <li class="navMainBtns">Playoffs</li> -->
                    
                    <li class="navMainBtns">
                        <a href="draft_recap.php">
                            <img src="img/icons/File text.svg" class="navMainIcon" alt="Draft Recap icon">Draft Recap
                        </a>
                    </li> 
                    <li class="navMainBtns">
                        <a href="league_information.php">
                            <img src="img\icons\icons8-information-50.png" alt="">League Information
                        </a>
                    </li>  
                </ul>
            </div>
        </div>