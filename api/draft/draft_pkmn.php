<?php

//Going to add error reporting by default
error_reporting(E_ALL);
ini_set('display_errors', 1);

//starts and or resumes php session. If I don't have this db can't be accessed. So for this specifically my site won't know which player is making a draft pick.
session_start();
// header is just saying: what will come from this php page is going to be json data and not html
header('Content-Type: application/json');
// This is just load at start and if it can't don't run script. 
require_once __DIR__ . '/../../includes/connection.php'; //This is saying. In this directory, go up two levels and look for includes

//This defines the limit for my draft
$tierLimits = 
[
    'OU' => 3,
    'UU' => 3,
    'RU' => 3,
    'NU' => 3,
];

// This will help me count uubl pokemon as ou pokemon and etc. Easier to do this here rather than in sql.
function mapToLeagueTier($tier) {
    $blMap = [
        'UUBL' => 'OU',
        'RUBL' => 'UU',
        'NUBL' => 'NU',
        // add more as needed
    ];
    
    return $blMap[$tier] ?? $tier; // default to itself if not in map
}

 

// -------------
// GET VARIABLES
// -------------

//this is a read only stream that has raw body of http request. When the front end sends data using fetch or ajax it will be in JSON. This just reads that information.
// example: {"showdown_pkmn":25}
//The "true" part just places that information into an array. $data = [ "showdown_pkmn" => 25];
// Without the "true" the information would be an object instead of an array
$data = json_decode(file_get_contents("php://input"), true);

$pokemonId = $data['showdown_pkmn'] ?? null; //which pokemon user wants to draft
$userId = $_SESSION['user_id'] ?? null; //currently logged in user
$seasonId = $_SESSION['season_id'] ?? null; //current draft season

//Below is how I tested to see if user name and season could be found
// echo json_encode([
//     'user_id' => $userId,
//     'season_id' => $seasonId
// ]);
// exit;



// ----------
// VALIDATION
// ----------
if(!$pokemonId || !$userId || !$seasonId)
    {
        http_response_code(400);
        echo json_encode(['error' => 'invalid request-missing pokemon, user or season']);
        exit;
    }





// ----------
// TURN ORDER
// ----------

//Get Current Pick

//Stmt is just statement. Basically my query
$draftInfoStmt = $conn->prepare("SELECT current_pick FROM draft_info WHERE season_id = ?");
$draftInfoStmt->bind_param("i", $seasonId);
$draftInfoStmt->execute();

$result = $draftInfoStmt->get_result();
$draftRow = $result->fetch_assoc();
$currentPick = $draftRow['current_pick'] ?? 1;

// Get Draft Order
$orderStmt = $conn->prepare("
    SELECT user_id
    FROM active_users
    WHERE season_id = ?
    ORDER BY draft_pick ASC
");
$orderStmt->bind_param("i",$seasonId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

$users = [];
while ($row = $orderResult->fetch_assoc())
    {
        $users[] = $row['user_id'];
    }

$playerCount = count($users);
if($playerCount === 0)
    {
        http_response_code(500);
        echo json_encode(['error' => 'No active users for this season']);
        exit;
    }

// snake draft math
$round = ceil($currentPick / $playerCount);
$indexInRound = ($currentPick - 1) % $playerCount;

if($round % 2 === 0)
    {
        $activeUserIndex = $playerCount - 1 - $indexInRound;
    }
else
    {
        $activeUserIndex = $indexInRound;
    }

$activeUserId = $users[$activeUserIndex] ?? null;

// Check to see who's turn it is

if($userId != $activeUserId)
    {
        http_response_code(403);
        echo json_encode(['error' => 'Not Your Turn']);
        exit;
    }

//----------------
// ENFORCE TIER LIMITS
//----------------


function enforceTierLimits($conn, $activeUserId, $seasonId, $pokemonId, $tierLimits)
{
    // Get tier
    $tierStmt = $conn->prepare("
        SELECT tier 
        FROM pkmn_tier 
        WHERE showdown_pkmn_id = ? AND season_id = ?
        LIMIT 1
    ");
    $tierStmt->bind_param("ii", $pokemonId, $seasonId);
    $tierStmt->execute();
    $tierResult = $tierStmt->get_result();
    $tierRow = $tierResult->fetch_assoc();
    $tierStmt->close();

    if (!$tierRow) {
        http_response_code(400);
        echo json_encode(['error' => 'Pokémon tier not found']);
        exit;
    }

    $tier = $tierRow['tier'];
    $mappedTier = mapToLeagueTier($tier);

    // Count drafted Pokémon in this tier
    $draftedStmt = $conn->prepare("
        SELECT t.tier 
        FROM drafted_pkmn dp
        JOIN pkmn_tier t 
          ON dp.showdown_pkmn = t.showdown_pkmn_id 
         AND dp.season_id = t.season_id
        WHERE dp.active_user = ? 
          AND dp.season_id = ?
    ");
    $draftedStmt->bind_param("ii", $activeUserId, $seasonId);
    $draftedStmt->execute();
    $result = $draftedStmt->get_result();

    $alreadyDraftedCount = 0;
    while ($row = $result->fetch_assoc()) {
        if (mapToLeagueTier($row['tier']) === $mappedTier) {
            $alreadyDraftedCount++;
        }
    }

    if (!isset($tierLimits[$mappedTier])) return;

    if ($alreadyDraftedCount >= $tierLimits[$mappedTier]) {
        http_response_code(403);
        echo json_encode([
            'error' => "You cannot draft more than {$tierLimits[$mappedTier]} Pokémon from the $mappedTier tier"
        ]);
        exit;
    }
}


// Get season-specific active_user ID
$activeUserStmt = $conn->prepare("
    SELECT id
    FROM active_users
    WHERE user_id = ? AND season_id = ?
    LIMIT 1
");
$activeUserStmt->bind_param("ii", $userId, $seasonId);
$activeUserStmt->execute();
$activeUserResult = $activeUserStmt->get_result();
$activeUserRow = $activeUserResult->fetch_assoc();
$activeUserStmt->close();

$activeUserId = $activeUserRow['id'] ?? null;

if (!$activeUserId) {
    http_response_code(403);
    echo json_encode(['error' => 'User not active in this season']);
    exit;
}

// ----------
// ENFORCE TIER LIMITS
// ----------
enforceTierLimits($conn, $activeUserId, $seasonId, $pokemonId, $tierLimits);


// ---------------
// DUPLICATE CHECK
// ---------------
$checkStmt = $conn->prepare("
    SELECT 1 FROM drafted_pkmn
    WHERE showdown_pkmn = ? AND season_id = ?
    LIMIT 1
");
$checkStmt->bind_param("ii", $pokemonId, $seasonId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    http_response_code(403);
    echo json_encode(['error' => 'This Pokémon has already been drafted']);
    exit;
}


// ----------
// INSERT DRAFT PICK
// ----------
$insertStmt = $conn->prepare("
    INSERT INTO drafted_pkmn (active_user, season_id, showdown_pkmn, pick_number)
    VALUES (?, ?, ?, ?)
");
$insertStmt->bind_param("iiii", $activeUserId, $seasonId, $pokemonId, $currentPick);

if(!$insertStmt->execute())
{
    http_response_code(500);
    echo json_encode(['error' => 'Failed to draft Pokémon']);
    exit;
}

$insertStmt->close();


// ----------
// ADVANCE DRAFT PICK
// ----------
$nextPickStmt = $conn->prepare("
    UPDATE draft_info
    SET current_pick = current_pick + 1
    WHERE season_id = ?
");
$nextPickStmt->bind_param("i", $seasonId);
$nextPickStmt->execute();
$nextPickStmt->close();


// ----------
// SUCCESS RESPONSE
// ----------
echo json_encode([
    'status' => 'success',
    'pokemon_id' => $pokemonId
]);
exit;

?>