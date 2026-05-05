<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Boot.php';
require_once '../app/Models/Klant.php';

requireLogin();

// Alleen beheerder mag boten wijzigen
if (($_SESSION['rol'] ?? '') !== 'beheerder') {
    header('Location: dashboard.php');
    exit;
}

$database = new Database();
$pdo = $database->connect();

$bootModel = new Boot($pdo);
$klantModel = new Klant($pdo);

// Haal het boot_id uit de URL
$boot_id = $_GET['id'] ?? null;

// Controleer of er een geldig boot_id is meegegeven
if (!$boot_id || !is_numeric($boot_id)) {
    header('Location: boten.php');
    exit;
}

// Haal de boot op uit de database
$boot = $bootModel->getById($boot_id);

// Controleer of de boot bestaat
if (!$boot) {
    header('Location: boten.php');
    exit;
}

// Haal de klant op die bij deze boot hoort
$klant = $klantModel->getById($boot['klant_id']);

// Controleer of de klant bestaat
if (!$klant) {
    header('Location: boten.php');
    exit;
}

$fout = '';

// Vul de variabelen eerst met bestaande gegevens
$klant_id = $klant['klant_id'];
$klantNaam = $klant['naam'];
$email = $klant['email'];
$telefoonnummer = $klant['telefoonnummer'];
$bootNaam = $boot['naam'];
$merk = $boot['merk'];

// Als het formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klantNaam = trim($_POST['klant_naam'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefoonnummer = trim($_POST['telefoonnummer'] ?? '');
    $bootNaam = trim($_POST['boot_naam'] ?? '');

    // Controleer of verplichte velden zijn ingevuld
    if (
        empty($klantNaam) ||
        empty($email) ||
        empty($telefoonnummer) ||
        empty($bootNaam)
    ) {
        $fout = 'Vul alle verplichte velden in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fout = 'Vul een geldig e-mailadres in.';
    } else {
        // Wijzig de klantgegevens
        $klantModel->update($klant_id, $klantNaam, $email, $telefoonnummer);

        // Wijzig alleen de bootnaam
        // Het merk blijft bewust hetzelfde
        $bootModel->updateZonderMerk($boot_id, $bootNaam);

        header('Location: boten.php?success=boot_gewijzigd');
        exit;
    }
}

$currentPage = 'boten';
$pageTitle = 'Boot wijzigen - Bootbeheersysteem';

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
                <h1>Boot wijzigen</h1>
                <p>Wijzig de klantgegevens en bootnaam. Het merk blijft hetzelfde.</p>
            </div>
        </section>

        <section class="content-card boot-wijzigen-card">

            <?php if (!empty($fout)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($fout); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <h2>Klantgegevens</h2>

                <div class="form-group">
                    <label for="klant_naam">Klantnaam</label>
                    <input 
                        type="text" 
                        name="klant_naam" 
                        id="klant_naam" 
                        value="<?= htmlspecialchars($klantNaam); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="<?= htmlspecialchars($email); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="telefoonnummer">Telefoonnummer</label>
                    <input 
                        type="text" 
                        name="telefoonnummer" 
                        id="telefoonnummer" 
                        value="<?= htmlspecialchars($telefoonnummer); ?>" 
                        required
                    >
                </div>

                <h2>Bootgegevens</h2>

                <div class="form-group">
                    <label for="boot_naam">Bootnaam</label>
                    <input 
                        type="text" 
                        name="boot_naam" 
                        id="boot_naam" 
                        value="<?= htmlspecialchars($bootNaam); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="merk">Merk</label>
                    <input 
                        type="text" 
                        name="merk" 
                        id="merk" 
                        value="<?= htmlspecialchars($merk); ?>" 
                        readonly
                    >
                    <small>Het merk kan niet worden aangepast.</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Wijzigingen opslaan</button>
                    <a href="boten.php" class="btn-secondary">Annuleren</a>
                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>