<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/connection.php';
session_start();

$seasonId = $_SESSION['season_id'] ?? null;
if (!$seasonId) {
    echo json_encode(["success" => false, "error" => "No season selected"]);
    exit;
}

$draftDate = $_POST['draft_date'] ?? null;
$seasonStart = $_POST['season_start'] ?? null;

$draftDate = !empty($draftDate) ? $draftDate : null;
$seasonStart = !empty($seasonStart) ? $seasonStart : null;

$rules = $_POST['rules'] ?? [];

try{

     if (!$seasonStart) {
        throw new Exception("Season start date is required.");
    }

    // Update season info in seasons table
    $stmt = $conn->prepare("
        UPDATE seasons
        SET start_date = ?, draft_date = ?
        WHERE season_id = ?
    ");

    // Use 'ssi' if both are strings and allow NULL
    $stmt->bind_param("ssi", $seasonStart, $draftDate, $seasonId);
    $stmt->execute();

    // Remove existing rules
    $stmt = $conn->prepare("DELETE FROM format_rules WHERE season_id = ?");
    $stmt->bind_param("i", $seasonId);
    $stmt->execute();

   // Only delete/insert rules if UPDATE succeeded
    if ($stmt->affected_rows >= 0) {

        // Remove existing rules
        $stmt = $conn->prepare("DELETE FROM format_rules WHERE season_id = ?");
        $stmt->bind_param("i", $seasonId);
        $stmt->execute();

        // Insert updated rules
        $stmt = $conn->prepare("INSERT INTO format_rules (season_id, content) VALUES (?, ?)");
        foreach ($rules as $rule) {
            $rule = trim($rule);
            if ($rule === "") continue;
            $stmt->bind_param("is", $seasonId, $rule);
            $stmt->execute();
        }
    }

    echo json_encode(["success" => true]);
}
    catch (Exception $e) 
    {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
$conn->close();