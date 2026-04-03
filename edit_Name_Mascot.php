<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    $seasonId = $_SESSION['season_id'] ?? null;


    require_once __DIR__ . '/includes/connection.php';

    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT team_name, team_mascot_pkmn FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['user_id'];

        $teamName = strtoupper($_POST['team_name']);

        if (strlen($teamName) > 5) {
            $error = "Team name must be 5 characters or less.";
        }

        $teamMascot = $_POST['team_mascot'] ?? '';



        if ($teamName && $teamMascot) {
            $sql = "UPDATE users 
                    SET team_name = ?, team_mascot_pkmn = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $teamName, $teamMascot, $userId);

            if ($stmt->execute()) 
            {
                // Added this to change the name in the Navbar
                $_SESSION['team_name'] = $teamName;
                $_SESSION['team_mascot_pkmn'] = $teamMascot;

                header("Location: profile.php");
                exit;
            } 
            else 
            {
                $error = "Error updating team.";
            }

            $stmt->close();
        } 

        else 
        {
            $error = "All fields are required.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="assets/js/script.js"></script>

    <title>Ascent - edit profile</title>
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
                <div class="pageTitle">Edit Team Name & Mascot</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>

        <div>
            <main class="centerMain">
                <section class="shortContentCont">

                    <?php if (!empty($success)): ?>
                        <div><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="editTeamCol">
                            <label for="">Team Name:</label>
                            <input type="text" name="team_name" oninput="this.value = this.value.toUpperCase()" maxlength="5" value="<?php echo htmlspecialchars($user['team_name'] ?? ''); ?>">
                        </div>
                        <div class="editTeamCol">
                            <label for=""> Team Mascot: </label>
                            <input type="text" name="team_mascot" value="<?php echo htmlspecialchars($user['team_mascot_pkmn'] ?? ''); ?>"> 
                        </div>
                        <div>
                            <button class="adminSettingsBtn" id="saveProfileChanges" type="submit">Save Changes</button>
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