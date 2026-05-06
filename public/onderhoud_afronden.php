<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Onderhoudsopdracht.php';

requireLogin();


// Controleer of het formulier via POST is verstuurd
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: onderhoud.php');
    exit;
}

// ID veilig ophalen
$opdracht_id = $_POST['opdracht_id'] ?? null;

// Controleren of het ID geldig is
if (empty($opdracht_id) || !ctype_digit((string)$opdracht_id)) {
    header('Location: onderhoud.php?melding=ongeldig');
    exit;
}

// Databaseverbinding maken
$database = new Database();
$pdo = $database->connect();

// Model gebruiken
$onderhoudsopdracht = new Onderhoudsopdracht($pdo);

// Opdracht afronden
$onderhoudsopdracht->markeerAlsAfgerond($opdracht_id);

// Terug naar onderhoudsoverzicht
header('Location: onderhoud.php?melding=afgerond');
exit;