<?php

require_once '../app/Helpers/auth.php';

requireLogin();

$naam = $_SESSION['naam'] ?? 'Gebruiker';
$rol = $_SESSION['rol'] ?? '';

$currentPage = 'onderhoud';
$pageTitle = 'Onderhoud - Bootbeheersysteem';

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="app-layout">

    <?php require_once '../app/Views/layouts/sidebar.php'; ?>

    <main class="main-content">

        <section class="topbar">
            <div>
                <h1>Onderhoud</h1>
                <p>Bekijk hier de openstaande onderhoudsopdrachten.</p>
            </div>

            <span class="role-badge">
                <?= htmlspecialchars($rol); ?>
            </span>
        </section>

        <section class="dashboard-intro">
            <h2>Onderhoudsoverzicht</h2>
            <p>Hier komen straks alle openstaande onderhoudsopdrachten te staan.</p>
        </section>

        <section class="content-card">
            <h3>Openstaande opdrachten</h3>
            <p>De tabel met onderhoudsopdrachten wordt in een volgende fase toegevoegd.</p>
        </section>

    </main>

</div>

</body>
</html>