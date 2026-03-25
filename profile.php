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
    <script src="assets/js/script.js"></script>

    <title>Profile</title>
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
                        <div class="pageTitle"> Profile</div>
                        <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                    </div>
                </header>
                <div>
                    <main>
                        <section class="shortContentCont">
                            <div>
                                Log Out:
                                <button><a href="logout.php">Log Out</a></button>
                            </div>
                        </section>
                    </main>
                </div>
                <?php include 'includes/footer.php'; ?>
         
        </div>
    </div>
</body>
</html>