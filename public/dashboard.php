<?php

session_start();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Bootbeheersysteem</title>
</head>
<body>

    <h1>Dashboard</h1>

    <p>Je bent ingelogd.</p>

    <?php if (isset($_SESSION['naam'])) : ?>
        <p>Welkom, <?= htmlspecialchars($_SESSION['naam']); ?>.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['rol'])) : ?>
        <p>Rol: <?= htmlspecialchars($_SESSION['rol']); ?></p>
    <?php endif; ?>

</body>
</html>