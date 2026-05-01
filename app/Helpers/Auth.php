<?php

// Start de sessie alleen als er nog geen sessie actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controleert of de gebruiker is ingelogd
function requireLogin()
{
    if (!isset($_SESSION['gebruiker_id'])) {
        header('Location: login.php');
        exit;
    }
}

// Geeft de naam van de ingelogde gebruiker terug
function getIngelogdeGebruikerNaam()
{
    return $_SESSION['naam'] ?? 'Gebruiker';
}

// Geeft de rol van de ingelogde gebruiker terug
function getIngelogdeGebruikerRol()
{
    return $_SESSION['rol'] ?? '';
}

// Controleert of iemand beheerder is
function requireAdmin()
{
    requireLogin();

    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'beheerder') {
        header('Location: geen_toegang.php');
        exit;
    }
}

// Handige functie om in menu's te controleren of iemand beheerder is
function isAdmin()
{
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'beheerder';
}