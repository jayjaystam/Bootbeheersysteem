<?php

require_once '../app/Helpers/auth.php';
require_once '../config/database.php';
require_once '../app/Models/Gebruiker.php';


requireLogin();


//alleen beheerder mag deze pagina gebruiken

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'beheerder') {
    header('Location: dashboard.php');
    exit;
}

$naam = $_SESSION['naam'] ?? 'gebruiker';
$rol = $_SESSION['rol'] ?? '';

$cuurentPage = 'werknemers';
$pageTitle = 'Werknemer toevoegen - Bootbeheersysteem';


$database = new Database();
$pdo = $database->connect();

$gebruikerModel = new Gebruiker(
    $pdo
);

$fout = '';
$succes = '';

$werknemernaam = '';
$email = '';
$gebruikernaam = '';

if (   )