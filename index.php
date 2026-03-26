<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

if (!isset($_SESSION['user_id'])) 
    {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;

    // Get latest replay

    $sql = $sql = "
    SELECT 
        m.replay_link,
        m.created_at,

        u1.gamerTag AS player1_name,
        u2.gamerTag AS player2_name

    FROM matchup m

    JOIN active_users au1 
        ON au1.id = m.player1_active_user_id
    JOIN users u1 
        ON u1.id = au1.user_id

    JOIN active_users au2 
        ON au2.id = m.player2_active_user_id
    JOIN users u2 
        ON u2.id = au2.user_id

    WHERE m.season_id = ?
      AND m.replay_link IS NOT NULL
      AND m.replay_link != ''

    ORDER BY m.created_at DESC
    LIMIT 1
";

    $replay_stmt = $conn->prepare($sql);
    $replay_stmt->bind_param("i", $seasonId);
    $replay_stmt->execute();

    $replayResult = $replay_stmt->get_result();
    $latestReplay = $replayResult->fetch_assoc();

    // Standings

    $standingSql = "
    SELECT users.gamerTag, 
    COUNT(matchup.winner_active_user_id) AS Wins
    FROM active_users
    JOIN users
    ON active_users.user_id = users.id
    LEFT JOIN matchup
    ON matchup.winner_active_user_id = active_users.id
    AND matchup.season_id = ?
    WHERE active_users.season_id = ?
    GROUP BY active_users.id, users.gamerTag
    ORDER BY Wins DESC;
    ";

    $standingStmt = $conn->prepare($standingSql);
    $standingStmt->bind_param("ii", $seasonId, $seasonId);
    $standingStmt->execute();

    $standingResult = $standingStmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Ascent - Overview</title>
</head>
<body>
    <div class="pageLayout">
        
        <?php include 'includes/navbar.php';?>

        <div class="pageContent">
            <header class="headerCont">
                <div class="seasonCont">
                    <div class="seasonBtn">Season <?php echo htmlspecialchars($seasonId); ?></div>
                </div>
                <div class="pageNameCont">
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                    <div class="pageTitle"> Overview</div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div id="overviewPageLayout">
                <main>
                    <section class="newsCont">
                        <div class="sectionTitle">News/Updates</div>
                        <article class="newsContent"> No new updates for this league</article>
                    </section>
                    <section class="playerDashCont">
                        <div class="sectionTitle">Player Dashboard</div>
                        <article class="playerDashContent"> 
                            <section id="homeRosterCont">
                                <section id="homeRosterHeader">Team Name</section>
                                <section id="homeRosterBox">
                                    <ul id="homePkmnList">
                                        <!-- Javascript will load results -->
                                    </ul>
                                </section>
                            </section>
                        </article>
                    </section>
                </main>
                <aside>
                    <section id="replayCont">
                        <div class="smallerSectionTitle">Replay</div>
                        <?php if ($latestReplay): ?>
                            <a href="<?php echo htmlspecialchars($latestReplay['replay_link']); ?>" 
                            target="_blank" 
                            class="replayBox">

                                <div id="replayTeams">
                                    <?php echo htmlspecialchars($latestReplay['player1_name']); ?>
                                    vs
                                    <?php echo htmlspecialchars($latestReplay['player2_name']); ?>
                                </div>

                                <div class="replayDate">
                                    <?php 
                                        $date = new DateTime($latestReplay['created_at']);
                                        echo $date->format('m/d/y g:i A');;
                                    ?>
                                </div>

                            </a>
                        <?php else: ?>
                            <div>No replays yet</div>
                        <?php endif; ?>
                    </section>
                    <section id="miniStandingsCont">
                        <div class="smallerSectionTitle">Standings</div>
                        <div id="miniStandings">
                            <?php if ($standingResult && $standingResult->num_rows > 0): ?>

                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Team</th>
                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $rank = 1;
                                            while ($row = $standingResult->fetch_assoc()): 
                                        ?>
                                            <tr>
                                                <td><?php echo $rank++; ?></td>
                                                <td><?php echo htmlspecialchars($row['gamerTag']); ?></td>
                                                
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>

                            <?php else: ?>
                                <div>No Standings Yet</div>
                            <?php endif; ?>
                        </div>
                    </section>
                    <section id="killLeaderBoardCont">
                            <div class="smallerSectionTitle">Kill Leader</div>
                            <div id="killLeader">Will update when league starts</div>             
                    </section>
                </aside>
            
            </div>
            <?php include 'includes/footer.php'; ?>
            
            
        </div>
    </div>
    
</body>
</html>