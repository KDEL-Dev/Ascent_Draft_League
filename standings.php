
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Ascent - Standings</title>
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
                <div class="pageTitle">Standings</div>
            </div>
        </header>

        <div>
            <main>
                <section class="contentCont">
                    <section id="standingsCont">
                        <table id="standingsTable">
                            <thead id="standingsHeader">
                                <tr>
                                    <th style="width: 15%;">Rank</th>
                                    <th style="width: 40%;">Teams</th>
                                    <th style="width: 15%;">Wins</th>
                                    <th style="width: 15%;">Losses</th>
                                    <th style="width: 15%;">+/-</th>
                                </tr>
                            </thead>
                            <tbody id="standingsBody">
                                <tr>
                                    <td>1</td>
                                    <td>sgs pete</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>5</td>
                                </tr>  
                                <tr>
                                    <td>1</td>
                                    <td>sgs pete</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>5</td>
                                </tr>  
                                <tr>
                                    <td>1</td>
                                    <td>sgs pete</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>5</td>
                                </tr>  
                                <tr>
                                    <td>1</td>
                                    <td>sgs pete</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>5</td>
                                </tr>  
                            </tbody>
                        </table>
                    </section>
                </section>
            </main>

            <?php include 'includes/footer.php'; ?>
        </div>

    </div>

</div>
</body>
</html>