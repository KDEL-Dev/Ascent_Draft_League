<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/connection.php';

$sql = "SELECT gamerTag FROM users";
$result = $conn->query($sql);

$gamerTags = [];

while ($row = $result->fetch_assoc()) {
    $gamerTags[] = $row["gamerTag"];
}

echo json_encode($gamerTags);
?>