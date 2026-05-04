<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Boot.php';
require_once '../app/Models/Klant.php';

requireLogin();

// alleen beheerder mag boten toevoegen 

if (($_SESSION['rol'] ?? '') !== 'beheerder') {
    header('location: dashboard.php');
    exit;
}


$database = new Database();
$pdo = $database->connect();

$bootModel = new Boot($pdo);
$klantModel = new klant($pdo);

$klanten = $klantModel->getAll();

$fout = '';

$klant_id = '';
$naam = '';
$merk = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klant_id = trim($_POST['klant_id'] ?? '');
    $naam = trim($_POST['naam'] ?? '');
    $merk = trim($_POST['merk'] ?? '');

    if (empty($klant_id) || empty($naam) || empty($merk)) {
        $fout = 'Vul alle verplichte velden in.';
    } else {
        $bootModel->create($klant_id, $naam, $merk);

        header('Location: boten.php?success=boot_toegevoegd');
        exit;
    }
}

$currentpage = 'boten';
$paginaTitel = 'boot toevoegen - bootbeheersysteem';

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
                <p>Voeg een nieuwe boot toe en koppel deze aan een klant.</p>
            </div>
        </section>

        <section class="content-card">

            <?php if (!empty($fout)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($fout); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($klanten)): ?>
                <div class="alert alert-error">
                    Er zijn nog geen klanten. Voeg eerst een klant toe voordat je een boot kunt toevoegen.
                </div>
            <?php else: ?>

                <form method="POST" action="">

                    <div class="form-group">
                        <label for="klant_id">Klant</label>
                        <select name="klant_id" id="klant_id" required>
                            <option value="">-- Kies een klant --</option>

                            <?php foreach ($klanten as $klant): ?>
                                <option value="<?= htmlspecialchars($klant['klant_id']); ?>"
                                    <?= ($klant_id == $klant['klant_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($klant['naam']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="naam">Bootnaam</label>
                        <input 
                            type="text" 
                            name="naam" 
                            id="naam" 
                            value="<?= htmlspecialchars($naam); ?>" 
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

            <?php endif; ?>

        </section>

    </main>

</div>

</body>
</html>