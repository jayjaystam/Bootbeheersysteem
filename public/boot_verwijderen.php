<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Boot.php';

requireLogin();

// Alleen beheerder mag boten verwijderen
if (($_SESSION['rol'] ?? '') !== 'beheerder') {
    header('Location: dashboard.php');
    exit;
}

$boot_id = $_GET['id'] ?? null;

// Controleer of er een geldig id is meegegeven
if (!$boot_id || !ctype_digit((string) $boot_id)) {
    header('Location: boten.php?error=ongeldig_id');
    exit;
}

$database = new Database();
$pdo = $database->connect();

$bootModel = new Boot($pdo);

// Controleer eerst of deze boot onderhoudsopdrachten heeft
if ($bootModel->heeftOnderhoudsopdrachten($boot_id)) {
    header('Location: boten.php?error=boot_heeft_onderhoud');
    exit;
}

// Probeer de boot te verwijderen
$verwijderd = $bootModel->delete($boot_id);

if ($verwijderd) {
    header('Location: boten.php?success=boot_verwijderd');
    exit;
}

header('Location: boten.php?error=verwijderen_mislukt');
exit;