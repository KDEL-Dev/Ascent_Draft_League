<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$matchupId = $_GET['matchup_id'] ?? null;
if (!$matchupId) {
    echo "Matchup not specified";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/styles/styles.css">
<title>Edit Matchup</title>
</head>
<body>

<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
        <header class="headerCont">
            <div class="pageTitle">Edit Matchup</div>
        </header>

        <main>
            <form id="edit_matchup_form">
                <input type="hidden" name="matchup_id" value="<?= $matchupId ?>">

                <h3>Team 1 Pokémon</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Pokemon</th>
                            <th>Kills</th>
                            <th>Deaths</th>
                        </tr>
                    </thead>
                    <tbody id="team1Body"></tbody>
                </table>

                <h3>Team 2 Pokémon</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Pokemon</th>
                            <th>Kills</th>
                            <th>Deaths</th>
                        </tr>
                    </thead>
                    <tbody id="team2Body"></tbody>
                </table>

                <label>Replay</label>
                <input type="text" name="replay_link" id="replayLink">

                <button type="submit">Save Changes</button>
            </form>
        </main>
    </div>
</div>

<script>
const matchupId = <?= json_encode($matchupId) ?>;
</script>
<script src="assets/js/script.js"></script>

</body>
</html>