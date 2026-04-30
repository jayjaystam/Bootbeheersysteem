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
            $_SESSION['gebruiker_id'] = $gebruiker['gebruiker_id'];
            $_SESSION['naam'] = $gebruiker['naam'];
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
</head>
<body>

    <h1>Inloggen</h1>

    <?php if (!empty($fout)) : ?>
        <p style="color: red;">
            <?= htmlspecialchars($fout); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="gebruikersnaam">Gebruikersnaam</label><br>
            <input
                type="text"
                id="gebruikersnaam"
                name="gebruikersnaam"
                value="<?= htmlspecialchars($gebruikersnaam); ?>"
            >
        </div>

        <br>

        <div>
            <label for="wachtwoord">Wachtwoord</label><br>
            <input
                type="password"
                id="wachtwoord"
                name="wachtwoord"
            >
        </div>

        <br>

        <button type="submit">Inloggen</button>
    </form>

</body>
</html>