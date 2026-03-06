<?php
// draft_pkmn.php
// -----------------
// Draft a Pokémon during the draft
// -----------------

// Enable error reporting for debugging (comment out in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/connection.php';

// -----------------
// Max Pokémon per user
// -----------------
$MAX_POKEMON_PER_USER = 6; // change this to whatever limit you want

// -----------------
// Validate input
// -----------------
$data = json_decode(file_get_contents("php://input"), true);
$pokemonId = $data['showdown_pkmn'] ?? null;
$userId = $_SESSION['user_id'] ?? null;
$seasonId = $_SESSION['season_id'] ?? 1;

if (!$pokemonId || !$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing Pokémon or user ID']);
    exit;
}

// -----------------
// Get current pick
// -----------------
$currentPickRes = mysqli_query($conn, 
"SELECT current_pick 
FROM draft_info 
WHERE season_id = $seasonId");
if (!$currentPickRes) 
    {
        http_response_code(500);
        echo json_encode(['error' => mysqli_error($conn)]);
        exit;
    }
$currentPickRow = mysqli_fetch_assoc($currentPickRes);

if (!$currentPickRow) {
    echo json_encode(['error' => 'No draft_info row for this season']);
    exit;
}

$currentPick = $currentPickRow['current_pick'];

// -----------------
// Get draft order
// -----------------



$usersRes = mysqli_query($conn, 
//tested and confirmed that this works
"SELECT user_id 
FROM active_users 
WHERE season_id = $seasonId 
ORDER BY draft_pick ASC");

if (!$usersRes) {
    http_response_code(500);
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$users = [];
while ($row = mysqli_fetch_assoc($usersRes)) {
    $users[] = $row['user_id'];
}
$playerCount = count($users);

if ($playerCount === 0) {
    http_response_code(500);
    echo json_encode(['error' => 'No active users for this season']);
    exit;
}

// -----------------
// Snake draft calculation
// -----------------
$round = ceil($currentPick / $playerCount);
$indexInRound = ($currentPick - 1) % $playerCount;

if ($round % 2 === 0) {
    // Even rounds go backward
    $activeUserIndex = $playerCount - 1 - $indexInRound;
} else {
    // Odd rounds go forward
    $activeUserIndex = $indexInRound;
}

$activeUserId = $users[$activeUserIndex];

// -----------------
// Check if it's this user's turn
// -----------------
if ($userId != $activeUserId) {
    http_response_code(403);
    echo json_encode(['error' => 'Not your turn']);
    exit;
}

// -----------------
// Determine next pick number safely
// -----------------
$nextPickRes = mysqli_query($conn, "SELECT IFNULL(MAX(pick_number),0)+1 AS next_pick FROM drafted_pkmn WHERE season_id = $seasonId");
$nextPickRow = mysqli_fetch_assoc($nextPickRes);
$nextPickNumber = $nextPickRow['next_pick'] ?? $currentPick;

// -----------------
// Get active_users.id for this user + season
// -----------------
$activeUserRes = mysqli_query($conn,
"SELECT id 
 FROM active_users 
 WHERE user_id = $userId 
 AND season_id = $seasonId
 LIMIT 1");

if (!$activeUserRes) {
    http_response_code(500);
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$activeUserRow = mysqli_fetch_assoc($activeUserRes);

if (!$activeUserRow) {
    http_response_code(400);
    echo json_encode(['error' => 'User is not active for this season']);
    exit;
}

$activeUserRowId = $activeUserRow['id'];




// -----------------
// Max Pokémon per user
// -----------------
$MAX_POKEMON_PER_USER = 6;

$countRes = mysqli_query($conn, "
    SELECT COUNT(*) AS drafted_count
    FROM drafted_pkmn
    WHERE season_id = $seasonId AND active_user = $activeUserRowId
");
$countRow = mysqli_fetch_assoc($countRes);
$draftedCount = $countRow['drafted_count'] ?? 0;

if ($draftedCount >= $MAX_POKEMON_PER_USER) {
    http_response_code(403);
    echo json_encode(['error' => "You have already drafted the maximum of $MAX_POKEMON_PER_USER Pokémon."]);
    exit;
}

// -----------------
// Insert drafted Pokémon
// -----------------
$stmt = mysqli_prepare($conn, "
    INSERT INTO drafted_pkmn (season_id, active_user, showdown_pkmn, pick_number, drafted_at)
    VALUES (?, ?, ?, ?, NOW())
");
mysqli_stmt_bind_param($stmt, "iiii", $seasonId, $activeUserRowId, $pokemonId, $nextPickNumber);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    echo json_encode(['error' => mysqli_stmt_error($stmt)]);
    exit;
}

mysqli_stmt_close($stmt);

// -----------------
// Advance current pick
// -----------------
mysqli_query($conn, "UPDATE draft_info SET current_pick = current_pick + 1 WHERE season_id = $seasonId");

// -----------------
// Return JSON for frontend
// -----------------
echo json_encode([
    'status' => 'success',
    'next_pick' => $currentPick + 1,
    'active_user_id' => $activeUserId
]);

mysqli_close($conn);