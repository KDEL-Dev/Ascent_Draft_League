<?php
    session_start();
    require_once 'includes/connection.php';

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>
    <title>Ascent - Draft Recap</title>
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
                    <div class="pageTitle"> Draft Recap </div>
                    <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                </div>
            </header>
            <div>
                <main class="centerMain">
                    <section class="roleContentCont">
                        <section id="recapFlexCont">
                            <div class="tableCont">
                                <table id="recapTable">
                                    <thead>    
                                        <tr class="thGradientBg" >
                                            <th>No.</th>
                                            <th style="min-width: 450px;">Pokemon</th>
                                            <th style="width: 125px;">Tier</th>
                                            <th style="width: 250px;">Team</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recapTableBody">
                                        <!-- Dynamically Created -->
                                    </tbody>
                                    <!-- <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td class="ouBadge"><div>OU</div></td>
                                        <td>SGS</td>
                                    </tr>
                                    <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td>Ou</td>
                                        <td>SGS</td>
                                    </tr>
                                    <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td>Ou</td>
                                        <td>SGS</td>
                                    </tr>
                                    <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td>Ou</td>
                                        <td>SGS</td>
                                    </tr>
                                    <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td>Ou</td>
                                        <td>SGS</td>
                                    </tr>
                                    <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td>Ou</td>
                                        <td>SGS</td>
                                    </tr>
                                    <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td>Ou</td>
                                        <td>SGS</td>
                                    </tr>
                                    <tr>                                    
                                        <td>1</td>
                                        <td>Landorus-T</td>
                                        <td>Ou</td>
                                        <td>SGS</td>
                                    </tr> -->
                                </table>
                            </div>
                        </section>
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>