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

$database = new Database();
$pdo = $database->connect();

$bootModel = new Boot($pdo);

$boot_id = $_GET['id'] ?? null;

// Controleer of er een geldig id is meegegeven
if (!$boot_id || !is_numeric($boot_id)) {
    header('Location: boten.php');
    exit;
}

// Controleer of de boot bestaat
$boot = $bootModel->getById($boot_id);

if (!$boot) {
    header('Location: boten.php');
    exit;
}

// Verwijder de boot
$bootModel->delete($boot_id);

header('Location: boten.php?success=boot_verwijderd');
exit;