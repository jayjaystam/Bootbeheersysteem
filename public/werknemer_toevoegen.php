<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Gebruiker.php';

requireLogin();

// Alleen beheerder mag deze pagina gebruiken
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'beheerder') {
    header('Location: dashboard.php');
    exit;
}

$naam = $_SESSION['naam'] ?? 'Gebruiker';
$rol = $_SESSION['rol'] ?? '';

$currentPage = 'werknemers';
$pageTitle = 'Werknemer toevoegen - Bootbeheersysteem';

$database = new Database();
$pdo = $database->connect();

$gebruikerModel = new Gebruiker($pdo);

$fout = '';
$succes = '';

$werknemerNaam = '';
$email = '';
$gebruikersnaam = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $werknemerNaam = trim($_POST['naam'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord = trim($_POST['wachtwoord'] ?? '');

    if (empty($werknemerNaam) || empty($email) || empty($gebruikersnaam) || empty($wachtwoord)) {
        $fout = 'Vul alle verplichte velden in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fout = 'Vul een geldig e-mailadres in.';
    } elseif (strlen($wachtwoord) < 8) {
        $fout = 'Het wachtwoord is te zwak. Gebruik minimaal 8 tekens.';
    } elseif ($gebruikerModel->gebruikersnaamBestaat($gebruikersnaam)) {
        $fout = 'Deze gebruikersnaam bestaat al.';
    } else {
        $opgeslagen = $gebruikerModel->createWerknemer(
            $werknemerNaam,
            $email,
            $gebruikersnaam,
            $wachtwoord
        );

        if ($opgeslagen) {
            $succes = 'Werknemer is succesvol toegevoegd.';

            $werknemerNaam = '';
            $email = '';
            $gebruikersnaam = '';
        } else {
            $fout = 'Er ging iets mis bij het opslaan van de werknemer.';
        }
    }
}

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
                <h1>Werknemer toevoegen</h1>
                <p>Maak een nieuw account aan voor een werknemer.</p>
            </div>
        </section>

        <section class="form-card">

            <?php if (!empty($fout)): ?>
                <div class="melding fout">
                    <?= htmlspecialchars($fout); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($succes)): ?>
                <div class="melding succes">
                    <?= htmlspecialchars($succes); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label for="naam">Naam werknemer</label>
                    <input 
                        type="text" 
                        id="naam" 
                        name="naam" 
                        value="<?= htmlspecialchars($werknemerNaam); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= htmlspecialchars($email); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="gebruikersnaam">Gebruikersnaam</label>
                    <input 
                        type="text" 
                        id="gebruikersnaam" 
                        name="gebruikersnaam" 
                        value="<?= htmlspecialchars($gebruikersnaam); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="wachtwoord">Wachtwoord</label>
                    <input 
                        type="password" 
                        id="wachtwoord" 
                        name="wachtwoord"
                    >
                    <small>Gebruik minimaal 8 tekens.</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Werknemer opslaan</button>
                    <a href="dashboard.php" class="btn-secondary">Annuleren</a>
                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>