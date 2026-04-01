<?php

    session_start();

    require_once __DIR__ . '/../../includes/connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;
    if (!$seasonId) die("Season ID missing.");

    $about = $_POST['about'] ?? '';
    $rules = $_POST['rules'] ?? '';

    $updateSql = "UPDATE league_information SET about = ?, rules = ? WHERE season_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssi", $about, $rules, $seasonId);
    $stmt->execute();

    header("Location: /ascent_draft_league/league_information.php");
    exit;
?>