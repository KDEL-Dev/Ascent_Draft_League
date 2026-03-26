<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

// Check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get season ID from query string
$seasonId = $_GET['season_id'] ?? null;
if (!$seasonId) {
    die("Season ID missing.");
}

// Fetch league info
$stmt = $conn->prepare("SELECT * FROM league_information WHERE season_id = ?");
$stmt->bind_param("i", $seasonId);
$stmt->execute();
$league = $stmt->get_result()->fetch_assoc();
if (!$league) {
    $league = ['about' => '', 'rules' => ''];
}

// Fetch season dates
$stmt = $conn->prepare("SELECT start_date, draft_date FROM seasons WHERE season_id = ?");
$stmt->bind_param("i", $seasonId);
$stmt->execute();
$season = $stmt->get_result()->fetch_assoc();
$draftDate = $season['draft_date'] ?? '';
$seasonStart = $season['start_date'] ?? '';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $about = $_POST['about'] ?? '';
    $rules = $_POST['rules'] ?? '';
    $draft_date = $_POST['draft_date'] ?? '';
    $season_start = $_POST['season_start'] ?? '';

    // Update league_information
    $stmt = $conn->prepare("INSERT INTO league_information (season_id, about, rules) VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE about = VALUES(about), rules = VALUES(rules)");
    $stmt->bind_param("iss", $seasonId, $about, $rules);
    $stmt->execute();

    // Update seasons table
    $stmt = $conn->prepare("UPDATE seasons SET draft_date = ?, start_date = ? WHERE season_id = ?");
    $stmt->bind_param("ssi", $draft_date, $season_start, $seasonId);
    $stmt->execute();

    $message = "League information updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit League Information</title>
<link rel="stylesheet" href="assets/styles/styles.css">
</head>
<body>
<div class="pageLayout">
    <?php include 'includes/navbar.php'; ?>

    <div class="pageContent">
        <header class="headerCont">
            <div class="seasonCont">
                <div class="seasonBtn">Season <?php echo htmlspecialchars($seasonId); ?></div>
            </div>
            <div class="pageNameCont">
                <div class="pageTitle">Edit League Information</div>
            </div>
        </header>

        <main>
            <?php if(isset($message)) echo "<p class='successMsg'>$message</p>"; ?>

            <form method="POST">
                <h3>Important Dates</h3>
                <label>Draft Date</label>
                <input type="date" name="draft_date" value="<?php echo htmlspecialchars($draftDate); ?>">

                <label>Season Start</label>
                <input type="date" name="season_start" value="<?php echo htmlspecialchars($seasonStart); ?>">

                <h3>About</h3>
                <textarea name="about" rows="5"><?php echo htmlspecialchars($league['about']); ?></textarea>

                <h3>Rules</h3>
                <textarea name="rules" rows="5"><?php echo htmlspecialchars($league['rules']); ?></textarea>

                <br>
                <button type="submit">Save Changes</button>
            </form>
        </main>
    </div>
</div>
</body>
</html>