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
    $draftDate = $_POST['draft_date'] ?? null;
    $startDate = $_POST['start_date'] ?? null;

    $updateSql = "UPDATE league_information SET about = ?, rules = ? WHERE season_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssi", $about, $rules, $seasonId);
    $stmt->execute();

    // Dates

    if (!empty($draftDate)) {
        $stmt = $conn->prepare("UPDATE seasons SET draft_date = ? WHERE season_id = ?");
        $stmt->bind_param("si", $draftDate, $seasonId);
        $stmt->execute();
    }

    if (!empty($startDate)) {
        $stmt = $conn->prepare("UPDATE seasons SET start_date = ? WHERE season_id = ?");
        $stmt->bind_param("si", $startDate, $seasonId);
        $stmt->execute();
    }

    header("Location: ../../league_information.php");
    exit;
?>