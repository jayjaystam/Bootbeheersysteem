<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Onderhoudsopdracht.php';

requireLogin();

$naam = $_SESSION['naam'] ?? 'Gebruiker';

$currentPage = 'onderhoud';
$pageTitle = 'Onderhoud - Bootbeheersysteem';

$database = new Database();
$pdo = $database->connect();

$onderhoudModel = new Onderhoudsopdracht($pdo);
$opdrachten = $onderhoudModel->getOpenstaandWithBootEnKlant();
$afgerondeOpdrachten = $onderhoudModel->getAfgerondWithBootEnKlant();

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
                <h1>Onderhoudsoverzicht</h1>
                <p>
                    Welkom, <?= htmlspecialchars($naam); ?>.
                    Hier zie je alle openstaande onderhoudsopdrachten.
                </p>
            </div>
        </section>

        <section class="content-card">

            <div class="section-header">
                <div>
                    <h2>Openstaande onderhoudsopdrachten</h2>
                    <p>Deze opdrachten moeten nog uitgevoerd worden.</p>
                </div>
            </div>

            <?php if (isset($_GET['melding']) && $_GET['melding'] === 'afgerond'): ?>
                <div class="melding succes">
                    Onderhoudsopdracht is succesvol afgerond.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['melding']) && $_GET['melding'] === 'ongeldig'): ?>
                <div class="melding fout">
                    Ongeldige onderhoudsopdracht.
                </div>
            <?php endif; ?>

            <?php if (empty($opdrachten)): ?>

                <div class="empty-state">
                    <p>Er zijn op dit moment geen openstaande onderhoudsopdrachten.</p>
                </div>

            <?php else: ?>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Geplande datum</th>
                            <th>Boot</th>
                            <th>Merk</th>
                            <th>Klant</th>
                            <th>Beschrijving</th>
                            <th>Status</th>
                            <th>Actie</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($opdrachten as $opdracht): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($opdracht['geplande_datum'])): ?>
                                        <?= date('d-m-Y', strtotime($opdracht['geplande_datum'])); ?>
                                    <?php else: ?>
                                        Geen datum
                                    <?php endif; ?>
                                </td>

                                <td><?= htmlspecialchars($opdracht['bootnaam']); ?></td>
                                <td><?= htmlspecialchars($opdracht['merk']); ?></td>
                                <td><?= htmlspecialchars($opdracht['klantnaam']); ?></td>
                                <td><?= htmlspecialchars($opdracht['beschrijving']); ?></td>

                                <td>
                                    <span class="status-badge status-openstaand">
                                        <?= htmlspecialchars($opdracht['status']); ?>
                                    </span>
                                </td>

                                <td>
                                    <form 
                                        action="onderhoud_afronden.php" 
                                        method="POST" 
                                        onsubmit="return confirm('Weet je zeker dat je deze opdracht wilt afronden?');"
                                    >
                                        <input 
                                            type="hidden" 
                                            name="opdracht_id" 
                                            value="<?= htmlspecialchars($opdracht['opdracht_id']); ?>"
                                        >

                                        <button type="submit" class="btn-afronden">
                                            Afronden
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>

        </section>

        <section class="content-card">
    <div class="section-header">
        <div>
            <h2>Onderhoudsgeschiedenis</h2>
            <p>Hier zie je alle onderhoudsopdrachten die al zijn afgerond.</p>
        </div>
    </div>

    <?php if (empty($afgerondeOpdrachten)): ?>

        <p class="empty-message">
            Er is nog geen afgerond onderhoud gevonden.
        </p>

    <?php else: ?>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Boot</th>
                        <th>Merk</th>
                        <th>Klant</th>
                        <th>Beschrijving</th>
                        <th>Geplande datum</th>
                        <th>Afgerond op</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($afgerondeOpdrachten as $opdracht): ?>
                        <tr>
                            <td><?= htmlspecialchars($opdracht['bootnaam']); ?></td>
                            <td><?= htmlspecialchars($opdracht['merk']); ?></td>
                            <td><?= htmlspecialchars($opdracht['klantnaam']); ?></td>
                            <td><?= htmlspecialchars($opdracht['beschrijving']); ?></td>

                            <td>
                                <?= !empty($opdracht['geplande_datum']) 
                                    ? date('d-m-Y', strtotime($opdracht['geplande_datum'])) 
                                    : '-'; ?>
                            </td>

                            <td>
                                <?= !empty($opdracht['afgerond_datum']) 
                                    ? date('d-m-Y', strtotime($opdracht['afgerond_datum'])) 
                                    : '-'; ?>
                            </td>

                            <td>
                                <span class="status-badge status-done">
                                    <?= htmlspecialchars($opdracht['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</section>

    </main>

</div>

</body>
</html>