<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("includes/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // --- Use prepared statements to prevent SQL injection ---
    $stmt = $conn->prepare("SELECT id, gamerTag FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) 
        {
            $row = $result->fetch_assoc();

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['gamerTag'] = $row['gamerTag'];
            $seasonResult = $conn->query("SELECT season_id FROM seasons WHERE is_active = 1 LIMIT 1");
            if($seasonRow = $seasonResult->fetch_assoc())
                {
                    $_SESSION['season_id'] = $seasonRow['season_id'];
                }
            else
                {
                    $_SESSION['season_id'] = 0;
                }

            header("Location: index.php"); // redirect to your draft page
            exit;
        } 
    else 
        {
            $login_error = "Invalid email or password";
        }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Login</title>
</head>
<body>
    <section id="loginLayout">
        <img id="loginLogo" src="img/Ascent Horizontal Text.png" alt="site logo">
        <form id="loginForm" method="post" action="login.php">
            <label for="email">Email: </label>
            <input type="email" id="email" class="formInput" name="email" required>
            <label for="password">Password: </label>
            <input type="password" name="password" class="formInput" id="password" required>
            <input type="submit" value="Login">
        </form>
        <?php if (!empty($login_error)) : ?>
            <p style="color:red;"><?php echo $login_error; ?></p>
        <?php endif; ?>
        <button><a href="register.html">Register</a></button>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>