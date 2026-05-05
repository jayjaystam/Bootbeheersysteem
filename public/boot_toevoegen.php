<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Boot.php';
require_once '../app/Models/Klant.php';

requireLogin();

// Alleen beheerder mag boten toevoegen
if (($_SESSION['rol'] ?? '') !== 'beheerder') {
    header('Location: dashboard.php');
    exit;
}

$database = new Database();
$pdo = $database->connect();

$bootModel = new Boot($pdo);
$klantModel = new Klant($pdo);

$fout = '';

$klantNaam = '';
$email = '';
$telefoonnummer = '';
$bootNaam = '';
$merk = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klantNaam = trim($_POST['klant_naam'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefoonnummer = trim($_POST['telefoonnummer'] ?? '');
    $bootNaam = trim($_POST['boot_naam'] ?? '');
    $merk = trim($_POST['merk'] ?? '');

    if (
        empty($klantNaam) ||
        empty($email) ||
        empty($telefoonnummer) ||
        empty($bootNaam) ||
        empty($merk)
    ) {
        $fout = 'Vul alle verplichte velden in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fout = 'Vul een geldig e-mailadres in.';
    } else {
        // Eerst wordt de nieuwe klant opgeslagen
        $nieuweKlantId = $klantModel->create($klantNaam, $email, $telefoonnummer);

        // Daarna wordt de boot gekoppeld aan deze nieuwe klant
        $bootModel->create($nieuweKlantId, $bootNaam, $merk);

        header('Location: boten.php?success=boot_toegevoegd');
        exit;
    }
}

$currentPage = 'boten';
$pageTitle = 'Boot toevoegen - Bootbeheersysteem';

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
                <h1>Boot toevoegen</h1>
                <p>Voeg een nieuwe boot toe met de gegevens van de klant.</p>
            </div>
        </section>

        <section class="content-card boot-toevoegen-card">

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
                        required
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Opslaan</button>
                    <a href="boten.php" class="btn-secondary">Annuleren</a>
                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>