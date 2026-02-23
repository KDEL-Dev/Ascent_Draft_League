<?php
    session_start();
    include("includes/connection.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $sql = "SELECT*FROM users
                    WHERE email = '$email'
                    AND password = '$password'";
            $result = $conn->query($sql);

            if($result->num_rows === 1)
                {
                    $row = $result->fetch_assoc();

                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['gamerTag'] = $row['gamerTag'];

                    header("Location: index.php");
                    exit;
                }
            else
                {
                    echo "invalid email or password";
                }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="styles/styles.css">
    <script src="script.js"></script>

    <title>Document</title>
</head>
<body>
    <section id="loginLayout">
        <img id="loginLogo" src="img/Ascent Horizontal Text.png" alt="site logo">
        <form id="loginForm" method="post" action="login.php">
            <label for="email">Email: </label>
            <input type="email" id="email" class="formInput" name="email">
            <label for="password">Password: </label>
            <input type="password" name="password" class="formInput" id="password">
            <input type="submit" value="login"></input>
        </form>
        <button><a href="register.html">register</a></button>
        
    </section>
</body>

<?php include 'includes/footer.php'; ?>

</html>

<?php
    mysqi_close($conn)
?>