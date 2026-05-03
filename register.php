<?php


session_start();
require_once 'includes/connection.php'; // your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Sanitize inputs
    $team_name = strtoupper(trim($_POST['team_name'])); // force uppercase for abbreviation
    $team_mascot = ucfirst(strtolower(trim($_POST['team_mascot']))); // first letter uppercase
    $email = trim($_POST['email']);

    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        die("Passwords do not match.");
    }

    // Only hash after confirming
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 2. Validate inputs
    if (strlen($team_name) > 5) {
        die("Team abbreviation must be 5 characters or less.");
    }
    if (empty($team_mascot)) {
        die("You must choose a Pokémon mascot.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // 3. Check if team_name already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE team_name = ?");
    $stmt->bind_param("s", $team_name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("This team abbreviation is already taken.");
    }

    // 4. Insert into database
    $stmt = $conn->prepare("INSERT INTO users (team_name, team_mascot_pkmn, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $team_name, $team_mascot, $email, $password_hashed);

    //ADDED BELOW

    if ($stmt->execute()) 
    {
        // 1. Store the user ID in session
        $userId = $conn->insert_id;
        $_SESSION['user_id'] = $userId;
        $_SESSION['team_name'] = $team_name;

        // 2. Add the user to the current season in active_users
        $seasonResult = $conn->query("SELECT season_id FROM seasons WHERE is_active = 1 LIMIT 1");
        if ($seasonRow = $seasonResult->fetch_assoc()) {
            $seasonId = $seasonRow['season_id'];

            $insertActive = $conn->prepare("
                INSERT INTO active_users (user_id, season_id, role, competitor)
                VALUES (?, ?, 'user', 'no')
            ");
            $insertActive->bind_param("ii", $userId, $seasonId);
            $insertActive->execute();
            $insertActive->close();

            // Optionally store role and season in session immediately
            $_SESSION['role'] = 'user';
            $_SESSION['season_id'] = $seasonId;
        }

        // 3. Redirect to index.php
        header("Location: index.php");
        exit;
    } 
    else 
    {
        die("Database error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/styles/styles.css">
<link rel="icon" type="image/png" sizes="32x32" href="img/Ascent-White.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/Ascent-White.png">


<title>Register</title>
</head>
<body>
<section id="registerLayout">
    <a href="login.php">
        <img id="registerLogo" src="img/Ascent Horizontal Text.svg" alt="site logo">
    </a>
    <form method="POST" action="register.php" id="registerForm">
        <div class="editTeamCol">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="editTeamCol">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="editTeamCol">
            <label>Confirm Password</label>
            <input type="password" name="password_confirm" required>
        </div>

        <div class="formFlex">
            <div class="editTeamCol">
                <label>Team Abbreviation (5 chars max)</label>
                <input 
                    type="text" 
                    name="team_name" 
                    maxlength="5" 
                    style="text-transform: uppercase;" 
                    placeholder="Jolt"
                    
                    required
                    id="teamNameInput">
            </div>
            <div class="editTeamCol">
                <label>Pokémon Mascot</label>
                <input 
                    type="text" 
                    name="team_mascot" 
                    maxlength="30" 
                    placeholder="Pikachu"
                    required>
            </div>
        </div>
        <div id="registerSubmitBtn">
            <button type="submit">Register</button>
        </div>    
    </form>
</section>
</body>
</html>