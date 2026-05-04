<?php

class Klant
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Alle klanten ophalen
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM klant ORDER BY naam ASC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eén klant ophalen op basis van id
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM klant WHERE klant_id = :id");
        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nieuwe klant toevoegen
    public function create($naam, $email, $telefoonnummer)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO klant (naam, email, telefoonnummer)
            VALUES (:naam, :email, :telefoonnummer)
        ");

        return $stmt->execute([
            'naam' => $naam,
            'email' => $email,
            'telefoonnummer' => $telefoonnummer
        ]);
    }

    // Bestaande klant wijzigen
    public function update($id, $naam, $email, $telefoonnummer)
    {
        $stmt = $this->pdo->prepare("
            UPDATE klant
            SET naam = :naam,
                email = :email,
                telefoonnummer = :telefoonnummer
            WHERE klant_id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'naam' => $naam,
            'email' => $email,
            'telefoonnummer' => $telefoonnummer
        ]);
    }
}