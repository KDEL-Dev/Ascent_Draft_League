<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();
require_once __DIR__ . '/../includes/connection.php';

$season_id = $_SESSION['season_id'] ?? 12; // current season
$user_id = $_SESSION['user_id'] ?? null;

// Maximum Pokémon per user
$MAX_POKEMON_PER_USER = 6;

$response = [
    'current_player' => null,
    'previous_pick' => null,
    'myDraftedCount' => 0,
    'maxPokemon' => $MAX_POKEMON_PER_USER
];

try {
    // ----------------- Previous pick -----------------
    $prevStmt = $conn->prepare("
        SELECT sp.name
        FROM drafted_pkmn dp
        JOIN showdown_pkmn sp ON dp.showdown_pkmn = sp.id
        WHERE dp.season_id = ?
        ORDER BY dp.pick_number DESC
        LIMIT 1
    ");
    $prevStmt->bind_param("i", $season_id);
    $prevStmt->execute();
    $prevResult = $prevStmt->get_result();

    if ($row = $prevResult->fetch_assoc()) {
        $response['previous_pick'] = $row['name'];
    }
    $prevStmt->close();

    // ----------------- Snake draft logic -----------------

    // 1️⃣ Get current pick
    $pickRes = mysqli_query($conn, "SELECT current_pick FROM draft_info WHERE season_id = $season_id");
    $pickRow = mysqli_fetch_assoc($pickRes);
    if (!$pickRow) {
        echo json_encode($response);
        exit;
    }
    $currentPick = $pickRow['current_pick'];

    // 2️⃣ Draft order
    $usersRes = mysqli_query($conn, "
        SELECT user_id
        FROM active_users
        WHERE season_id = $season_id
        ORDER BY draft_pick ASC
    ");
    $users = [];
    while ($row = mysqli_fetch_assoc($usersRes)) {
        $users[] = $row['user_id'];
    }
    $playerCount = count($users);
    if ($playerCount === 0) {
        echo json_encode($response);
        exit;
    }

    // 3️⃣ Snake math
    $round = ceil($currentPick / $playerCount);
    $indexInRound = ($currentPick - 1) % $playerCount;

    if ($round % 2 === 0) {
        $activeUserIndex = $playerCount - 1 - $indexInRound;
    } else {
        $activeUserIndex = $indexInRound;
    }

    $activeUserId = $users[$activeUserIndex] ?? null;

    // 4️⃣ Current player gamerTag
    if ($activeUserId) {
        $tagRes = mysqli_query($conn, "SELECT gamerTag FROM users WHERE id = $activeUserId LIMIT 1");
        $tagRow = mysqli_fetch_assoc($tagRes);
        if ($tagRow) {
            $response['current_player'] = $tagRow['gamerTag'];
        }
    }

    // ----------------- Count drafted Pokémon for this user -----------------
    if ($user_id) {
        $myDraftRes = mysqli_query($conn, "
            SELECT COUNT(*) AS drafted_count
            FROM drafted_pkmn
            WHERE season_id = $season_id AND active_user = $user_id
        ");
        $myDraftRow = mysqli_fetch_assoc($myDraftRes);
        $response['myDraftedCount'] = $myDraftRow['drafted_count'] ?? 0;
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();