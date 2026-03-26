<?php 


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Forces json to be outputted
header('content-type: application/json');
require_once __DIR__ . '/../../includes/connection.php';
// Connects me to the DB
session_start();

// I store this to use later in query
if (!$seasonId) {
    echo json_encode([
        "draft_date" => "",
        "season_start" => "",
        "rules" => []
    ]);
    exit;
}

// Fetch season start and draft date
$stmt = $conn->prepare("SELECT start_date, draft_date FROM seasons WHERE season_id = ?");
$stmt->bind_param("i", $seasonId);
$stmt->execute();
$res = $stmt->get_result();
$seasonData = $res->fetch_assoc();

// save my statement that just grabs all the content from format_rules table
$stmt = $conn->prepare("
    SELECT content 
    FROM format_rules 
    WHERE season_id = ?; 
");
// This is the safe way of injecting info into my query. Believe it is also a way to quickly change seasons based on session in the future.
$stmt->bind_param("i", $seasonId);
// use the query
$stmt->execute();
$result = $stmt->get_result();

// Create an empty array to hold my items
$ruleFormatList = [];
while($row = $result->fetch_assoc())
    {
        $ruleFormatList[] = $row['content']; //Note to self. Not having the [] at the end of ruleFormatList made it so that the array was overwriting itself instead of appending each new item.
    }

// Pushes the info out as JSON
echo json_encode(
    [
        "draft_date" => $seasonData["draft_date"] ?? "",
        "season_start" => $seasonData['start_date'] ?? "",
        "rules" => $ruleFormatList
    ]);


$conn->close();

?>
