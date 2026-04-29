<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

if (!isset($_SESSION['user_id'])) 
    {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;

    // News

    $news_Sql = "
        SELECT news
        FROM league_information
        WHERE season_id = ?;
    ";

    $news_Stmt = $conn->prepare($news_Sql);
    $news_Stmt->bind_param("i", $seasonId);
    $news_Stmt->execute();

    $newsResult = $news_Stmt->get_result();
    $news = $newsResult->fetch_assoc();

    // Get latest replay

    $sql = "
    SELECT 
        m.replay_link,
        m.created_at,

        u1.team_name AS player1_name,
        u2.team_name AS player2_name

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

    $replayResult = $replay_stmt->get_result();     //Stores the metadata and not actually can be read by php
    $latestReplay = $replayResult->fetch_assoc();   //Fetching turns data into something PHP can use which in this case I believe is an array

    // Standings

    $standingSql = "
    SELECT users.team_name, 
    COUNT(matchup.winner_active_user_id) AS Wins
    FROM active_users
    JOIN users
    ON active_users.user_id = users.id
    LEFT JOIN matchup
    ON matchup.winner_active_user_id = active_users.id
    AND matchup.season_id = ?
    WHERE active_users.season_id = ?
    AND competitor= 'yes'
    GROUP BY active_users.id, users.team_name
    HAVING wins > 0
    ORDER BY Wins DESC;
    ";

    $standingStmt = $conn->prepare($standingSql);
    $standingStmt->bind_param("ii", $seasonId, $seasonId);
    $standingStmt->execute();

    $standingResult = $standingStmt->get_result();

    // Kill LeaderBoard

    $killSql =  "
        SELECT showdown_pkmn.name, 
        SUM(mps.kills) AS total_kills
        FROM match_pokemon_stats mps
        JOIN roster_pkmn
        ON mps.roster_pkmn_id = roster_pkmn.id
        JOIN showdown_pkmn
        ON showdown_pkmn.id = roster_pkmn.showdown_pkmn
        WHERE roster_pkmn.season_id = ?
        GROUP BY showdown_pkmn.name
        ORDER BY total_kills DESC
        LIMIT 3;
    ";

    $killStmt = $conn->prepare($killSql);
    $killStmt->bind_param("i", $seasonId);
    $killStmt->execute();

    $killResult = $killStmt->get_result();
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

    <title>Ascent - Overview</title>
</head>
<body>
    <button id="hamburgerBtn">☰</button>
    <div class="pageLayout">
        
        <?php include 'includes/navbar.php';?>

        <div class="pageContent">
            <header class="headerCont">
                
                <?php include 'includes/season_setting_header.php';?>

                <div class="pageNameCont">
                    <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
                    <div class="pageTitle"> Overview</div>
                    <img src="img/icons/PokeBall_Icon-color.svg" alt="pokeball icon">
                </div>
            </header>
            <div id="overviewPageLayout">
                <main id="mainSpaced">
                    <section class="newsCont">
                        <div class="sectionTitle">News/Updates</div>
                        <article class="newsContent">
                            <p><?= htmlspecialchars($news['news']) ?></p>
                        </article>
                    </section>
                    <section class="playerDashCont">
                        <div class="sectionTitle">Player Dashboard</div>
                        <article class="playerDashContent"> 
                            Under Construction
                            <!-- Turning off for now. Javascript dynamically loads this
                            <section id="homeRosterCont">
                                <section id="homeRosterHeader">Team Name</section>
                                <section id="homeRosterBox">
                                    <ul id="homePkmnList">
                                        
                                    </ul>
                                </section>
                            </section> 
                            -->
                        </article>
                    </section>
                </main>
                <aside>
                    <section id="replayCont">
                        <div class="smallerSectionTitle" id="indexReplayTitle">Replay</div>
                        <?php if ($latestReplay): ?>
                            <a href="<?php echo htmlspecialchars($latestReplay['replay_link']); ?>" 
                            target="_blank" 
                            class="replayBox">
                                <div id="replayTeams">
                                    <?php echo htmlspecialchars($latestReplay['player1_name']); ?>
                                    <span id="replayVs">VS</span>
                                    <?php echo htmlspecialchars($latestReplay['player2_name']); ?>
                                </div>
                                <!-- My Overlay -->
                                <div class="replayOverlay">
                                    Click to Watch
                                </div>
                            </a>
                        <div class="replayDate">
                            <?php 
                                $date = new DateTime($latestReplay['created_at']);
                                echo $date->format('m/d/y g:i A');;
                            ?>
                        </div>
                        <?php else: ?>
                            <div id="noReplayText">No replays yet</div>
                        <?php endif; ?>
                    </section>
                    <a href="standings.php">
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
                                                    <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>

                                <?php else: ?>
                                    <div id="emptyStandings">No Standings Yet</div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </a>
                    <a href="statistics.php">
                        <section id="killLeaderBoardCont">
                                <div class="smallerSectionTitle">Kill Leader</div>
                                <div id="killLeader">

                                

                                    <?php if ($killResult && $killResult->num_rows > 0): ?>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Pkmn</th>
                                                    <th>Kills</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $rank = 1;
                                                    while ($row = $killResult->fetch_assoc()): 
                                                ?>
                                                    <tr>
                                                        <td><?php echo $rank++; ?></td>
                                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['total_kills']); ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <div>No Standings Yet</div>
                                    <?php endif; ?>
                                </div>             
                        </section>
                    </a>
                </aside>
            
            </div>
            <?php include 'includes/footer.php'; ?>
            
            
        </div>
    </div>
    
</body>
</html>