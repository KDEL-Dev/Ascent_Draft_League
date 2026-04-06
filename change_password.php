<?php
    session_start();
    require_once 'includes/connection.php';

    // User must be logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['user_id'];
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // 1. Check new password matches confirmation
        if ($new !== $confirm) {
            die("New password and confirmation do not match.");
        }

        // 2. Fetch current hashed password from DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        // 3. Verify current password
        if (!password_verify($current, $hashedPassword)) {
            echo "<script>
                alert('Current password is incorrect!');
                window.location.href = 'change_password.php';
                </script>";
                exit;
            }

        // 4. Hash new password
        $newHashed = password_hash($new, PASSWORD_DEFAULT);

        // 5. Update DB
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newHashed, $userId);

        if ($stmt->execute()) {
            echo "<script>alert('Password changed successfully!');</script>";
        }
        else 
        {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit News</title>

<link rel="stylesheet" href="assets/styles/styles.css">
<script src="assets/js/script.js"></script>

</head>
<body>
<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
         <header class="headerCont">
            <div class="seasonCont">
                <div class="seasonBtn">
                    Season <?php echo htmlspecialchars($seasonId); ?>
                </div>
            </div>
            <div class="pageNameCont">
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
                <div class="pageTitle">Change password</div>
                <img src="img/icons/PokeBall_Icon.svg" alt="pokeball icon">
            </div>
        </header>
        <div>
            <main class="centerMain">
                <section class="shortContentCont">
                    <section class="contentBtnCont">
                        <a id="newMatchBtn" href="profile.php">Return</a>
                    </section>
                    <form method="POST" action="change_password.php">
                        <div class="editTeamCol">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required>
                        </div>
                        <div class="editTeamCol">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" required>
                        </div>
                        <div class="editTeamCol">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required>
                        </div>
                        <button type="submit">Change Password</button>
                    </form>
                    
                </section>
            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>
</div>
</body>
</html>