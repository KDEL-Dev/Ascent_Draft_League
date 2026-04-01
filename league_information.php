<?php
    session_start();
    require_once __DIR__ . '/includes/connection.php';

    // Check user login
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    // Get season ID from query string
    $seasonId = $_SESSION['season_id'] ?? null;
    if (!$seasonId) {
        die("Season ID missing.");
    }

    // READ FUNCTION - About and Rules

    $infoSql = "
        SELECT * FROM `league_information`
        WHERE season_id = ?
    ";

    $stmt = $conn->prepare($infoSql);
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();

    $result = $stmt->get_result();
    $infoResult = $result->fetch_assoc();

    // READ FUNCTION - Date

    $dateSql = "
        SELECT draft_date, start_date
        FROM seasons
        WHERE season_id = ?;
    ";

    $dateStmt = $conn->prepare($dateSql);
    $dateStmt->bind_param("i",$seasonId);
    $dateStmt->execute();

    $dateResult = $dateStmt->get_result();
    $dateInfo = $dateResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="/ascent_draft_league/assets/js/script.js"></script>

    <title>Ascent - League Information</title>
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
                    <div class="pageTitle"> League Information</div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">
                        <section id="leagueInfoCont">
                            <section id="leagueInfoRow">
                                <section id="whatIsCont" class="leagueInfoSect">
                                    <h2>What is Ascent Draft League?</h2>
                                    <p><?= htmlspecialchars($infoResult['about']) ?></p>                                
                                </section>
                                <section id="importantDatesCont" class="leagueInfoSect">
                                   <h2>Important Dates</h2>
                                    <table>
                                        <tr>
                                            <th>Draft Date</th>
                                            <td><?= htmlspecialchars($dateInfo['draft_date']) ?></td>    
                                        </tr>
                                        <tr>
                                            <th>Season Start</th>
                                            <td><?= htmlspecialchars($dateInfo['start_date']) ?></td>
                                        </tr>
                                    </table>
                            

                                </section>
                            </section>
                            <section id="formatRulesCont" class="leagueInfoSect">
                                <h2>Format and Rules</h2>
                                <ul id="ruleList">
                                    <?php
                                    foreach (explode("\n", $infoResult['rules']) as $rule) {
                                        $rule = trim($rule);
                                        if (!empty($rule)) {
                                            echo "<li>" . htmlspecialchars($rule) . "</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </section>
                        </section>
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>

    

</html>