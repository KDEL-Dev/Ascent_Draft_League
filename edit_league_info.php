<?php
    session_start();
    require_once __DIR__ . '/includes/connection.php';

    // Check login
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;
    if (!$seasonId) die("Season ID missing.");

    // Fetch current info
    $infoSql = "SELECT * FROM league_information WHERE season_id = ?";
    $stmt = $conn->prepare($infoSql);
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();
    $result = $stmt->get_result();
    $infoResult = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit League Information</title>

<link rel="stylesheet" href="assets/styles/styles.css">
<script src="assets/js/script.js"></script>

</head>
<body>
<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
         <header class="headerCont">
            <div class="seasonCont">
                <div class="seasonBtn">
                    Season <?php echo htmlspecialchars($seasonId); ?>
                </div>
            </div>
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                <div class="pageTitle">Edit League Info Content</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>
        <div>
            <main class="centerMain">
                <section class="shortContentCont">
                    <section class="contentBtnCont">
                        <a id="newMatchBtn" href="admin.php">Return</a>
                    </section>
                    <form id="editLeagueInfoForm" action="/ascent_draft_league/api/league_information/save_league_info.php" method="post">
                        <div class="editTeamCol">
                            <label for="about">About</label>
                            <textarea name="about" id="about" rows="5" style="width: 100%;"><?= htmlspecialchars($infoResult['about']) ?></textarea>
                        </div>
                        <div class="editTeamCol">
                            <label for="rules">Format & Rules</label>
                            <textarea name="rules" id="rules" rows="10"><?= htmlspecialchars($infoResult['rules']) ?></textarea>
                        </div>
                        <div>
                           <button type="submit">Save Changes</button>
                        </div>
                    </form>
                    
                </section>
            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</div>
</body>
</html>