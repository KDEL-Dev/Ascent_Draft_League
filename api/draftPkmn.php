<!-- DELETE AND START AGAIN -->

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'includes/connection.php';
?>

<?php
include 'includes/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$teamId = $data['teamId'];
$pokemonId = $data['pokemonId'];

// Prevent duplicate drafts
$stmt = $conn->prepare("SELECT id FROM showdown_pkmn WHERE pokemon_id = ?");
$stmt->bind_param("i", $pokemonId);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    echo json_encode(['status' => 'already drafted']);
    exit;
}

// Insert Pokémon into the team
$stmt = $conn->prepare("INSERT INTO showdown_pkmn (team_id, pokemon_id) VALUES (?, ?)");
$stmt->bind_param("ii", $teamId, $pokemonId);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>