<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }


    $seasonId = $_SESSION['season_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Ascent - Edit Role</title>
</head>
<body>
<div class="pageLayout">

    <?php include 'includes/navbar.php';?> 

    <div class="pageContent">

        <header class="headerCont">
            <div class="seasonCont">
                <div class="seasonBtn">
                    Season <?php echo htmlspecialchars($seasonId); ?>
                </div>
            </div>
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                <div class="pageTitle">Edit Role</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <div>
            <main class="centerMain">
                <section class="roleContentCont">
                    <section class="contentBtnCont">
                        <a id="newMatchBtn" href="admin.php">Return</a>
                    </section>
                    <div id="centerTable">
                        <div id="editRolePage" data-season="<?= htmlspecialchars($seasonId) ?>"></div>

                        <table id="roleTable">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Team Name</th>
                                    <th>Team Mascot</th>
                                    <th>role</th>
                                    <th>competitor</th>
                                    <th>season</th>
                                    <th>created at</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>

            <?php include 'includes/footer.php'; ?>
        </div>

    </div>

</div>
</body>
</html>