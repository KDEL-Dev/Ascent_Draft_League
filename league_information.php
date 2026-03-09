<?php
session_start();

if (!isset($_SESSION['user_id'])) 
    {
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
    <script src="script.js"></script>

    <title>League Information</title>
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
                    <div class="pageTitle"> League Information</div>
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">
                        
                    </section>
                </main>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>