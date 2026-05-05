<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Boot.php';
require_once '../app/Models/Onderhoudsopdracht.php';

// Alleen ingelogde gebruikers mogen verder
requireLogin();

// Alleen beheerders mogen deze pagina gebruiken
if ($_SESSION['rol'] !== 'beheerder') {
    header('Location: dashboard.php');
    exit;
}

// Databaseverbinding maken
$database = new Database();
$pdo = $database->connect();

// Models aanmaken
$bootModel = new Boot($pdo);
$onderhoudModel = new Onderhoudsopdracht($pdo);

// Alle boten ophalen voor de dropdown
$boten = $bootModel->getAllWithKlant();

$fout = '';
$succes = '';

// Controleren of het formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boot_id = $_POST['boot_id'] ?? '';
    $beschrijving = trim($_POST['beschrijving'] ?? '');
    $geplande_datum = $_POST['geplande_datum'] ?? '';

    // Validatie
    if (empty($boot_id) || empty($beschrijving) || empty($geplande_datum)) {
        $fout = 'Vul alle verplichte velden in.';
    } else {
        $opgeslagen = $onderhoudModel->create($boot_id, $beschrijving, $geplande_datum);

        if ($opgeslagen) {
            $succes = 'Onderhoudsopdracht is succesvol ingepland.';
        } else {
            $fout = 'Er is iets fout gegaan bij het opslaan.';
        }
    }
}

$naam = $_SESSION['naam'] ?? 'Gebruiker';
$rol = $_SESSION['rol'] ?? '';

$currentPage = 'planning';
$pageTitle = 'Planning - Bootbeheersysteem';

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
                <h1>Planning</h1>
                <p>Plan een nieuwe onderhoudsopdracht in voor een boot.</p>
            </div>
        </section>

        <section class="content-card">

            <h2>Onderhoud inplannen</h2>

            <?php if (!empty($fout)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($fout); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($succes)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($succes); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="form-card">

                <div class="form-group">
                    <label for="boot_id">Boot</label>
                    <select name="boot_id" id="boot_id" required>
                        <option value="">-- Kies een boot --</option>

                        <?php foreach ($boten as $boot): ?>
                            <option value="<?= htmlspecialchars($boot['boot_id']); ?>">
                                <?= htmlspecialchars($boot['bootnaam']); ?>
                                -
                                <?= htmlspecialchars($boot['merk']); ?>
                                |
                                <?= htmlspecialchars($boot['klantnaam']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="beschrijving">Beschrijving onderhoud</label>
                    <textarea 
                        name="beschrijving" 
                        id="beschrijving" 
                        rows="5" 
                        placeholder="Bijvoorbeeld: motor controleren, romp schoonmaken of jaarlijkse onderhoudsbeurt."
                        required
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="geplande_datum">Geplande datum</label>
                    <input 
                        type="date" 
                        name="geplande_datum" 
                        id="geplande_datum" 
                        required
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Onderhoud inplannen</button>
                    <a href="dashboard.php" class="btn-secondary">Annuleren</a>
                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>