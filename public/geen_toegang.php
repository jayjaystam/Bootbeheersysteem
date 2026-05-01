<?php

require_once '../app/Helpers/auth.php';

requireLogin();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Geen toegang</title>
</head>
<body>

    <h1>Geen toegang</h1>

    <p>Je hebt geen rechten om deze pagina te bekijken.</p>

    <p>
        Je bent ingelogd als:
        <strong><?= htmlspecialchars(getIngelogdeGebruikerRol()); ?></strong>
    </p>

    <a href="dashboard.php">Terug naar dashboard</a>

</body>
</html>