<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
require_once 'includes/connection.php'; // your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Sanitize inputs
    $team_name = strtoupper(trim($_POST['team_name'])); // force uppercase for abbreviation
    $team_mascot = ucfirst(strtolower(trim($_POST['team_mascot']))); // first letter uppercase
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

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
    $stmt->bind_param("ssss", $team_name, $team_mascot, $email, $password);

    if ($stmt->execute()) {
        // 5. Store the user ID in session
        $_SESSION['user_id'] = $conn->insert_id;

        // 6. Redirect to index.php
        header("Location: index.php");
        exit;
    } else {
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


<title>Register</title>
</head>
<body>
<section id="registerLayout">
    <form method="POST" action="register.php">
        <label>Team Abbreviation (5 chars max)</label>
        <input 
            type="text" 
            name="team_name" 
            maxlength="5" 
            style="text-transform: uppercase;" 
            required
            id="teamNameInput"
        >

        <label>Pokémon Mascot</label>
        <input 
            type="text" 
            name="team_mascot" 
            maxlength="30" 
            required
        >

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>
    </form>
</section>
</body>
</html>