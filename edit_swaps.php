<?php
    session_start();
    require_once __DIR__ . '/includes/connection.php';

    // Auth check
    if (!isset($_SESSION['user_id']) || 
        !in_array($_SESSION['role'], ['admin', 'owner'])) 
    {
        header("Location: index.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;
    if (!$seasonId) die("Season ID missing.");

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Swaps</title>

<link rel="stylesheet" href="assets/styles/styles.css">
<script src="assets/js/script.js"></script>
</head>

<body>
<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
        <header class="headerCont">

            <?php include 'includes/season_setting_header.php';?>

            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg">
                <div class="pageTitle">Edit Swap Count</div>
                <img src="img/icons/PokeBall_Icon.svg">
            </div>
        </header>

        <main class="centerMain">
            <section class="shortContentCont">

                <section class="contentBtnCont">
                    <a href="admin.php">Return</a>
                </section>

                <form action="/ascent_draft_league/api/pokebox/reset_swaps.php" method="POST">

                    <div class="editTeamCol">
                        <label for="swapCount">New Swap Count</label>
                        <input 
                            type="number" 
                            name="swapCount" 
                            id="swapCount" 
                            min="0" 
                            placeholder="Enter swaps (e.g. 3)" 
                            required
                        >
                    </div>

                    <button type="submit">Apply to All Users</button>

                </form>

            </section>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</div>
</body>
</html>