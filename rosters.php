<!-- Must have this at the start of every page -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="script.js"></script>

    <title>Rosters</title>
</head>
<body>
    <div class="pageLayout">

        <?php include 'includes/navbar.php';?>

        <div class="pageContent">
            <header class="headerCont">
                <div class="seasonCont">
                    <div class="seasonBtn">Season 1</div>
                </div>
                <div class="pageNameCont">
                    <div class="pageTitle"> Rosters</div>
                </div>
            </header>
            <div class="pageLayout">
                <main>
                    <section class="contentCont">
                        <div class="teamCont">
                            <div class="teamName">team1</div>
                            <ul class="rosterPkmn">
                                <li class="pkmnNameTier">
                                    <div>pkmn1</div>
                                    <div>OU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn2</div>
                                    <div>OU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn3</div>
                                    <div>OU</div>
                                 </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn4</div>
                                    <div>UU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn5</div>
                                    <div>UU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn6</div>
                                    <div>UU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn7</div>
                                    <div>RU</div>
                                 </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn8</div>
                                    <div>RU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn9</div>
                                    <div>RU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn10</div>
                                    <div>NU</div>
                                </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn11</div>
                                    <div>NU</div>
                                 </li>
                                <li class="pkmnNameTier">
                                    <div>pkmn12</div>
                                    <div>NU</div>
                                </li>
                            </ul>
                        </div>
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>