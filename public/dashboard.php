<?php

require_once '../app/Helpers/auth.php';

requireLogin();

$naam = $_SESSION['naam'] ?? 'Gebruiker';
$rol = $_SESSION['rol'] ?? '';
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Bootbeheersysteem</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="app-layout">

    <aside class="sidebar">
        <h2>Bootbeheer</h2>

        <nav>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="boten.php">Boten</a>
            <a href="onderhoud.php">Onderhoud</a>

            <?php if ($rol === 'beheerder'): ?>
                <a href="planning.php">Planning</a>
            <?php endif; ?>
        </nav>

        <a href="logout.php" class="logout-link">Uitloggen</a>
    </aside>

    <main class="main-content">

        <section class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Welkom, <?= htmlspecialchars($naam); ?>.</p>
            </div>

            <span class="role-badge">
                <?= htmlspecialchars($rol); ?>
            </span>
        </section>

        <section class="dashboard-intro">
            <h2>Overzicht</h2>
            <p>Kies hieronder welk onderdeel je wilt openen.</p>
        </section>

        <section class="dashboard-grid">

            <a href="boten.php" class="dashboard-tile">
                <h3>Boten</h3>
                <p>Bekijk de boten en klantgegevens.</p>
            </a>

            <a href="onderhoud.php" class="dashboard-tile">
                <h3>Onderhoud</h3>
                <p>Bekijk openstaande onderhoudsopdrachten.</p>
            </a>

            <?php if ($rol === 'beheerder'): ?>
                <a href="planning.php" class="dashboard-tile">
                    <h3>Planning</h3>
                    <p>Plan nieuwe onderhoudsopdrachten in.</p>
                </a>
            <?php endif; ?>

        </section>

    </main>

</div>

</body>
</html>