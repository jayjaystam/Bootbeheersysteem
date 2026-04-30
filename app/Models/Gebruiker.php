<?php

class Gebruiker
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function zoekOpGebruikersnaam($gebruikersnaam)
    {
        $sql = "SELECT * FROM gebruiker WHERE gebruikersnaam = :gebruikersnaam LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'gebruikersnaam' => $gebruikersnaam
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}