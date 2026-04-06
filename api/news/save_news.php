<?php

    session_start();

    require_once __DIR__ . '/../../includes/connection.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $seasonId = $_SESSION['season_id'] ?? null;
    if (!$seasonId) die("Season ID missing.");

    $news = $_POST['news'] ?? '';
    
    $updateSql = "UPDATE league_information SET news = ? WHERE season_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $news, $seasonId);
    $stmt->execute();

    header("Location: /ascent_draft_league/index.php");
    exit;
?>