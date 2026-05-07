<?php

class Gebruiker
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Zoekt een gebruiker op gebruikersnaam voor het inloggen
    public function zoekOpGebruikersnaam($gebruikersnaam)
    {
        $sql = "SELECT * FROM gebruiker WHERE gebruikersnaam = :gebruikersnaam LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'gebruikersnaam' => $gebruikersnaam
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Controleert of een gebruikersnaam al bestaat
    public function gebruikersnaamBestaat($gebruikersnaam)
    {
        $sql = "SELECT gebruiker_id FROM gebruiker WHERE gebruikersnaam = :gebruikersnaam LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'gebruikersnaam' => $gebruikersnaam
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Maakt een nieuw werknemeraccount aan
    public function createWerknemer($naam, $email, $gebruikersnaam, $wachtwoord)
    {
        $wachtwoordHash = password_hash($wachtwoord, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO gebruiker (naam, email, gebruikersnaam, wachtwoord_hash, rol)
            VALUES (:naam, :email, :gebruikersnaam, :wachtwoord_hash, 'werknemer')
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'naam' => $naam,
            'email' => $email,
            'gebruikersnaam' => $gebruikersnaam,
            'wachtwoord_hash' => $wachtwoordHash
        ]);
    }
}