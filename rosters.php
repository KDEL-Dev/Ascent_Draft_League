<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$seasonId = $_SESSION['season_id'] ?? null;

require_once __DIR__ . '/includes/connection.php';

// Simple query using active_user as team identifier
$stmt = $conn->prepare("
    SELECT 
        u.gamerTag,
        sp.name,
        pt.tier
    FROM roster_pkmn rp
    JOIN showdown_pkmn sp 
        ON rp.showdown_pkmn = sp.id
    LEFT JOIN pkmn_tier pt
        ON pt.showdown_pkmn_id = sp.id
        AND pt.season_id = rp.season_id
    JOIN active_users au
        ON rp.active_user = au.id
    JOIN users u
        ON au.user_id = u.id
    WHERE rp.season_id = ?
    ORDER BY u.gamerTag, pt.tier
");

$stmt->bind_param("i", $seasonId);
$stmt->execute();
$result = $stmt->get_result();

$teams = [];
while ($row = $result->fetch_assoc()) {
    $teams[$row['gamerTag']][] = $row; // now the team name is the gamerTag
    $tiers[$row['tier']][] = $row; //Testing this to see if this is how i get tiers
}

$tierOrder = ['OU', 'UUBL', 'UU', 'RUBL', 'RU', 'NUBL', 'NU','PUBL', 'PU','ZUBL','ZU'];

foreach ($teams as $teamName => &$pokemonList) {
    usort($pokemonList, function($a, $b) use ($tierOrder) {
        $posA = array_search(strtoupper($a['tier']), $tierOrder);
        $posB = array_search(strtoupper($b['tier']), $tierOrder);

        $posA = $posA === false ? count($tierOrder) : $posA;
        $posB = $posB === false ? count($tierOrder) : $posB;

        return $posA - $posB;
    });
}
unset($pokemonList); // break reference
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="script.js"></script>

    <title>Rosters</title>
</head>
<body>
    <div class="pageLayout">

        <?php include 'includes/navbar.php';?>

        <div class="pageContent">
            <header class="headerCont">
                <div class="seasonCont">
                    <div class="seasonBtn">Season <?php echo htmlspecialchars($seasonId); ?></div>
                </div>
                <div class="pageNameCont">
                    <div class="pageTitle"> Rosters</div>
                </div>
            </header>
            <div>
                <main>
                    <section class="contentCont">

                        <?php foreach ($teams as $teamName => $pokemonList): ?>
                            <div class="teamCont">
                                <div class="teamName"><?= htmlspecialchars($teamName); ?></div>
                                <ul class="rosterPkmn">
                                    <?php foreach ($pokemonList as $pkmn): ?>
                                        <li class="pkmnNameTier">
                                            <div><?= htmlspecialchars($pkmn['name']); ?></div>
                                                                                        
                                            <?php  
                                                
                                                $tierMap = [
                                                    'OU' => 'ou',
                                                    'UUBL' => 'ou',

                                                    'UU' => 'uu',
                                                    'RUBL' => 'uu',

                                                    'RU' => 'ru',
                                                    'NUBL' => 'ru',

                                                    'NU' => 'nu',
                                                    'PUBL' => 'nu',

                                                    'PU' => 'nu',
                                                    'ZUBL' => 'nu',
                                                    'ZU' => 'nu'

                                                ];
                                                $tier = strtoupper($pkmn['tier']);
                                                $baseTier = $tierMap[$tier] ?? 'default';
                                            ?>
                                            <div class="<?= $baseTier ?>-RosterColor">
                                                <?= htmlspecialchars($tier); ?>
                                            </div>


                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>

                    </section>
                </main>
                <?php include 'includes/footer.php'; ?>
            </div>
            
        </div>
        
    </div>
    
</body>
</html>