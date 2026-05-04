<?php

require_once '../config/database.php';
require_once '../app/Helpers/auth.php';
require_once '../app/Models/Boot.php';

requireLogin();

$naam = $_SESSION['naam'] ?? 'Gebruiker';
$rol = $_SESSION['rol'] ?? '';

$currentPage = 'boten';
$pageTitle = 'Boten - Bootbeheersysteem';

// Databaseverbinding maken
$database = new Database();
$pdo = $database->connect();

// Boot model gebruiken
$bootModel = new Boot($pdo);
$boten = $bootModel->getAllWithKlant();

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
                <h1>Boten</h1>
                <p>Bekijk hier het overzicht van boten en klantgegevens.</p>
            </div>

            <span class="role-badge">
                <?= htmlspecialchars($rol); ?>
            </span>
        </section>

        <section class="dashboard-intro">
            <h2>Botenoverzicht</h2>
            <p>Hier zie je alle boten met de bijbehorende klantgegevens.</p>
        </section>

        <section class="content-card">
            <div class="card-header">
                <h3>Overzicht boten</h3>

                <?php if ($rol === 'beheerder'): ?>
                    <a href="boot_toevoegen.php" class="button">
                        Boot toevoegen
                    </a>
                <?php endif; ?>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bootnaam</th>
                        <th>Merk</th>
                        <th>Klantnaam</th>
                        <th>E-mail</th>
                        <th>Telefoonnummer</th>

                        <?php if ($rol === 'beheerder'): ?>
                            <th>Acties</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($boten)): ?>
                        <tr>
                            <td colspan="<?= $rol === 'beheerder' ? '6' : '5'; ?>">
                                Er zijn nog geen boten gevonden.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($boten as $boot): ?>
                            <tr>
                                <td><?= htmlspecialchars($boot['bootnaam']); ?></td>
                                <td><?= htmlspecialchars($boot['merk']); ?></td>
                                <td><?= htmlspecialchars($boot['klantnaam']); ?></td>
                                <td><?= htmlspecialchars($boot['email']); ?></td>
                                <td><?= htmlspecialchars($boot['telefoonnummer']); ?></td>

                                <?php if ($rol === 'beheerder'): ?>
                                    <td>
                                        <a href="boot_wijzigen.php?id=<?= $boot['boot_id']; ?>">
                                            Wijzigen
                                        </a>
                                        |
                                        <a href="boot_verwijderen.php?id=<?= $boot['boot_id']; ?>"
                                           onclick="return confirm('Weet je zeker dat je deze boot wilt verwijderen?');">
                                            Verwijderen
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>

</div>

</body>
</html>