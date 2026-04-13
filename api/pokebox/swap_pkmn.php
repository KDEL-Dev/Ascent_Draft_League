<?php


    header('Content-Type: application/json');
    require_once __DIR__ . '/../../includes/connection.php';
    session_start();

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['season_id'])) {
        echo json_encode(["status" => "error", "error" => "Not authenticated"]);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $seasonId = $_SESSION['season_id'];

    $data = json_decode(file_get_contents("php://input"), true);

    $addId  = $data['add'] ?? null;
    $dropId = $data['drop'] ?? null;

    if (!$addId || !$dropId) {
        echo json_encode(["status" => "error", "error" => "Missing parameters"]);
        exit;
    }

    /**
     * STEP 1: GET FREE AGENT
     */
    $stmt = $conn->prepare("
        SELECT s.id, s.name, t.tier
        FROM showdown_pkmn s
        LEFT JOIN pkmn_tier t 
        ON s.id = t.showdown_pkmn_id AND t.season_id = ?
        WHERE s.id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $seasonId, $addId);
    $stmt->execute();
    $addPkmn = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$addPkmn) {
        echo json_encode(["status" => "error", "error" => "Invalid add Pokémon"]);
        exit;
    }

    /**
     * STEP 2: GET DROP + VERIFY OWNERSHIP
     */
    $stmt = $conn->prepare("
        SELECT rp.id AS roster_id, s.id, t.tier
        FROM roster_pkmn rp
        JOIN showdown_pkmn s ON rp.showdown_pkmn = s.id
        LEFT JOIN pkmn_tier t 
            ON t.showdown_pkmn_id = s.id AND t.season_id = ?
        JOIN active_users au ON au.id = rp.active_user
        WHERE rp.id = ?
        AND rp.season_id = ?
        AND rp.is_active = 1
        AND au.user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("iiii", $seasonId, $dropId, $seasonId, $userId);
    $stmt->execute();
    $dropPkmn = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$dropPkmn) {
        echo json_encode(["status" => "error", "error" => "Invalid or unauthorized drop"]);
        exit;
    }

    /**
     * STEP 3: TIER GROUP CHECK
     */
    function getTierGroup($tier) {
        $groups = [
            "OU" => "OU", "UUBL" => "OU",
            "UU" => "UU", "RUBL" => "UU",
            "RU" => "RU", "NUBL" => "RU",
            "NU" => "NU", "PUBL" => "NU"
        ];
        return $groups[$tier] ?? null;
    }

    $addGroup = getTierGroup($addPkmn['tier']);
    $dropGroup = getTierGroup($dropPkmn['tier']);

    if ($addGroup !== $dropGroup) {
        echo json_encode([
            "status" => "error",
            "error" => "Tier mismatch"
        ]);
        exit;
    }

    /**
     * STEP 4: CHECK IF ADD POKEMON ALREADY IN ROSTER
     */
    $stmt = $conn->prepare("
        SELECT rp.id
        FROM roster_pkmn rp
        JOIN active_users au ON au.id = rp.active_user
        WHERE rp.showdown_pkmn = ?
        AND rp.season_id = ?
        AND rp.is_active = 1
        AND au.user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("iii", $addId, $seasonId, $userId);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($exists) {
        echo json_encode(["status" => "error", "error" => "You already own this Pokémon"]);
        exit;
    }

    /**
     * STEP 5: SWAP TRANSACTION
     */
    $conn->begin_transaction();

    try {

        // deactivate drop
        $stmt = $conn->prepare("
            UPDATE roster_pkmn
            SET is_active = 0
            WHERE id = ?
            AND season_id = ?
        ");
        $stmt->bind_param("ii", $dropId, $seasonId);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Drop update failed");
        }
        $stmt->close();

        // get active_user id properly (SAFE)
        $stmt = $conn->prepare("
            SELECT id 
            FROM active_users
            WHERE user_id = ? AND season_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("ii", $userId, $seasonId);
        $stmt->execute();
        $activeUser = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$activeUser) {
            throw new Exception("Active user not found");
        }

        // insert new pokemon
        $stmt = $conn->prepare("
            INSERT INTO roster_pkmn (active_user, showdown_pkmn, season_id, is_active)
            VALUES (?, ?, ?, 1)
        ");
        $stmt->bind_param(
            "iii",
            $activeUser['id'],
            $addId,
            $seasonId
        );
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        echo json_encode([
            "status" => "success",
            "message" => "Swap completed"
        ]);

    } catch (Exception $e) {
        $conn->rollback();

        echo json_encode([
            "status" => "error",
            "error" => "Swap failed",
            "debug" => $e->getMessage()
        ]);
    }

    $conn->close();