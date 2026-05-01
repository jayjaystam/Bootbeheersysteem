<?php

require_once '../app/Helpers/Auth.php';

requireLogin();

$naam = getIngelogdeGebruikerNaam();
$rol = getIngelogdeGebruikerRol();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Bootbeheersysteem</title>
</head>
<body>

    <h1>Dashboard</h1>

    <p>Welkom, <?= htmlspecialchars($naam); ?>.</p>
    <p>Je bent ingelogd als: <?= htmlspecialchars($rol); ?>.</p>

    <nav>
        <a href="dashboard.php">Dashboard</a> |
        <a href="logout.php">Uitloggen</a>
    </nav>

    <hr>

    <p>Deze pagina is beveiligd. Je kunt deze pagina alleen bekijken als je bent ingelogd.</p>

</body>
</html>