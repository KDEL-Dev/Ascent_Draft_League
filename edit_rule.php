<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/assets/styles/styles.css">
    <script src="/assets/js/script.js"></script>

    <title>Document</title>
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
                    <div class="pageTitle"> Edit Rules </div>
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">
                        <section>                            
                            <form action="POST" id="myEditForm">
                                <div id="ruleInputs"></div>

                                
                                <input type="submit">submit</button>

                            </form>
                        </section>
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>