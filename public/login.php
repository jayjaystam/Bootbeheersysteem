<?php

session_start();

require_once '../config/database.php';
require_once '../app/Models/Gebruiker.php';

$database = new Database();
$pdo = $database->connect();

$gebruikerModel = new Gebruiker($pdo);

$gebruikersnaam = '';
$fout = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord = trim($_POST['wachtwoord'] ?? '');

    if (empty($gebruikersnaam) || empty($wachtwoord)) {
        $fout = 'Vul alle verplichte velden in.';
    } else {
        $gebruiker = $gebruikerModel->zoekOpGebruikersnaam($gebruikersnaam);

if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord_hash'])) {
    session_start();

    $_SESSION['gebruiker_id'] = $gebruiker['gebruiker_id'];
    $_SESSION['naam'] = $gebruiker['naam'];
    $_SESSION['gebruikersnaam'] = $gebruiker['gebruikersnaam'];
    $_SESSION['rol'] = $gebruiker['rol'];

    header('Location: dashboard.php');
    exit;
} else {
    $fout = 'Gebruikersnaam of wachtwoord is onjuist.';
}
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen - Bootbeheersysteem</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">

    <main class="login-container">

        <section class="login-card">

            <div class="login-header">
                <h1>Inloggen</h1>
                <p>Log in om toegang te krijgen tot het bootbeheersysteem.</p>
            </div>

            <?php if (!empty($fout)) : ?>
                <div class="login-error">
                    <?= htmlspecialchars($fout); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="login-form">

                <div class="login-form-group">
                    <label for="gebruikersnaam">Gebruikersnaam</label>
                    <input
                        type="text"
                        id="gebruikersnaam"
                        name="gebruikersnaam"
                        value="<?= htmlspecialchars($gebruikersnaam ?? ''); ?>"
                    >
                </div>

                <div class="login-form-group">
                    <label for="wachtwoord">Wachtwoord</label>
                    <input 
                        type="password" 
                        id="wachtwoord" 
                        name="wachtwoord"
                    >
                </div>

                <label class="login-show-password">
                    <input type="checkbox" onclick="toggleWachtwoord()">
                    Wachtwoord tonen
                </label>

                <button type="submit" name="inloggen" class="login-button">
                    Inloggen
                </button>

            </form>

        </section>

    </main>

    <script>
        function toggleWachtwoord() {
            const wachtwoordVeld = document.getElementById('wachtwoord');

            if (wachtwoordVeld.type === 'password') {
                wachtwoordVeld.type = 'text';
            } else {
                wachtwoordVeld.type = 'password';
            }
        }
    </script>

</body>
</html>