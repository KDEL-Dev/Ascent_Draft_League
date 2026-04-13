<?php

    //Going to add error reporting by default
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);

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
            'NUBL' => 'RU', // NUBL was accidentally adding to RU
            'PUBL' => 'NU'
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





    // ----------------
    // TURN ORDER
    // ----------------
    $draftInfoStmt = $conn->prepare("SELECT current_pick FROM draft_info WHERE season_id = ?");
    $draftInfoStmt->bind_param("i", $seasonId);
    $draftInfoStmt->execute();
    $currentPick = $draftInfoStmt->get_result()->fetch_assoc()['current_pick'] ?? 1;
    $draftInfoStmt->close();

    // Get draft order for this season
    $orderStmt = $conn->prepare("SELECT user_id FROM active_users WHERE season_id = ? AND competitor = 'yes' ORDER BY draft_pick ASC");
    $orderStmt->bind_param("i", $seasonId);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();

    $users = [];
    while ($row = $orderResult->fetch_assoc()) {
        $users[] = $row['user_id']; // global user ID
    }
    $orderStmt->close();

    if (empty($users)) {
        http_response_code(500);
        echo json_encode(['error' => 'No active users for this season']);
        exit;
    }

    // -----------------
    // Snake draft logic
    // -----------------

    $playerCount = count($users);
    $round = ceil($currentPick / $playerCount);
    $indexInRound = ($currentPick - 1) % $playerCount;
    $activeUserGlobalId = ($round % 2 === 0) ? $users[$playerCount - 1 - $indexInRound] : $users[$indexInRound];

    if ($userId != $activeUserGlobalId) {
        http_response_code(403);
        echo json_encode(['error' => 'Not your turn']);
        exit;
    }

    // ----------------
    // GET ACTIVE_USER.ID
    // ----------------
    $activeUserStmt = $conn->prepare("SELECT id FROM active_users WHERE user_id = ? AND season_id = ?  LIMIT 1");
    $activeUserStmt->bind_param("ii", $userId, $seasonId);
    $activeUserStmt->execute();
    $activeUserRow = $activeUserStmt->get_result()->fetch_assoc();
    $activeUserId = $activeUserRow['id'] ?? null;
    $activeUserStmt->close();

    if (!$activeUserId) {
        http_response_code(403);
        echo json_encode(['error' => 'User not active in this season']);
        exit;
    }

    // ----------------
    // ENFORCE TIER LIMITS
    // ----------------
    $tierStmt = $conn->prepare("SELECT tier FROM pkmn_tier WHERE showdown_pkmn_id = ? AND season_id = ? LIMIT 1");
    $tierStmt->bind_param("ii", $pokemonId, $seasonId);
    $tierStmt->execute();
    $tierRow = $tierStmt->get_result()->fetch_assoc();
    $tierStmt->close();

    if (!$tierRow) {
        http_response_code(400);
        echo json_encode(['error' => 'Pokémon tier not found']);
        exit;
    }

    $mappedTier = mapToLeagueTier($tierRow['tier']);

    // Count how many Pokémon already drafted by this user in this tier
    $countStmt = $conn->prepare("
        SELECT t.tier
        FROM drafted_pkmn dp
        JOIN pkmn_tier t ON dp.showdown_pkmn = t.showdown_pkmn_id AND dp.season_id = t.season_id
        WHERE dp.active_user = ? AND dp.season_id = ?
    ");
    $countStmt->bind_param("ii", $activeUserId, $seasonId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();

    $alreadyDraftedCount = 0;
    while ($row = $countResult->fetch_assoc()) {
        if (mapToLeagueTier($row['tier']) === $mappedTier) {
            $alreadyDraftedCount++;
        }
    }
    $countStmt->close();

    if (isset($tierLimits[$mappedTier]) && $alreadyDraftedCount >= $tierLimits[$mappedTier]) {
        http_response_code(403);
        echo json_encode(['error' => "You cannot draft more than {$tierLimits[$mappedTier]} Pokémon from the $mappedTier tier"]);
        exit;
    }

    // ----------------
    // CHECK DUPLICATE PICK FOR SEASON
    // ----------------
    $duplicateStmt = $conn->prepare("
        SELECT 1 FROM drafted_pkmn
        WHERE active_user = ? AND season_id = ? AND showdown_pkmn = ? LIMIT 1
    ");
    $duplicateStmt->bind_param("iii", $activeUserId, $seasonId, $pokemonId);
    $duplicateStmt->execute();
    $duplicateResult = $duplicateStmt->get_result();
    $duplicateStmt->close();

    if ($duplicateResult->num_rows > 0) {
        http_response_code(403);
        echo json_encode(['error' => 'You have already drafted this Pokémon']);
        exit;
    }

    $conn->begin_transaction();

    try {

        // ----------------
        // INSERT DRAFT PICK
        // ----------------
        $insertStmt = $conn->prepare("
            INSERT INTO drafted_pkmn (active_user, season_id, showdown_pkmn, pick_number)
            VALUES (?, ?, ?, ?)
        ");
        $insertStmt->bind_param("iiii", $activeUserId, $seasonId, $pokemonId, $currentPick);

        if (!$insertStmt->execute()) {
            throw new Exception("Failed to insert draft pick");
        }

        $insertStmt->close();


        // ----------------
        // INSERT INTO ROSTER
        // ----------------
        $rosterStmt = $conn->prepare("
            INSERT INTO roster_pkmn (season_id, active_user, showdown_pkmn)
            VALUES (?, ?, ?)
        ");
        $rosterStmt->bind_param("iii", $seasonId, $activeUserId, $pokemonId);

        if (!$rosterStmt->execute()) {
            throw new Exception("Failed to insert roster Pokémon");
        }

        $rosterStmt->close();


        // ----------------
        // ADVANCE PICK
        // ----------------

       // Increment first
        $nextPickStmt = $conn->prepare("
            UPDATE draft_info
            SET current_pick = current_pick + 1
            WHERE season_id = ?
        ");
        $nextPickStmt->bind_param("i", $seasonId);

        if (!$nextPickStmt->execute()) {
            throw new Exception("Failed to advance draft pick");
        }
        $nextPickStmt->close();


        // THEN check updated value
        $checkStmt = $conn->prepare("
            SELECT current_pick, total_picks 
            FROM draft_info 
            WHERE season_id = ?
        ");
        $checkStmt->bind_param("i", $seasonId);
        $checkStmt->execute();
        $row = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($row && $row['current_pick'] > $row['total_picks']) {
            $endStmt = $conn->prepare("
                UPDATE draft_info 
                SET draft_finished = 1 
                WHERE season_id = ?
            ");
            $endStmt->bind_param("i", $seasonId);
            $endStmt->execute();
            $endStmt->close();
        }


        // everything succeeded
        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'current_pick' => $row['current_pick'] ?? null
        ]);
        exit;

    } 

    catch (Exception $e) 
    {

        // something failed → undo everything
        $conn->rollback();

        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
?>