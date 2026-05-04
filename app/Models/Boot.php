<?php

class Boot
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Alle boten ophalen met de klantgegevens erbij
    public function getAllWithKlant()
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                b.boot_id,
                b.naam AS bootnaam,
                b.merk,
                k.naam AS klantnaam,
                k.email,
                k.telefoonnummer
            FROM boot b
            INNER JOIN klant k ON b.klant_id = k.klant_id
            ORDER BY b.naam ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     // Haalt één boot op via het boot_id
    // Deze functie gebruiken we straks bij boot_wijzigen.php
    public function getById($boot_id)
    {
        $sql = "
            SELECT 
                boot_id,
                klant_id,
                naam,
                merk
            FROM boot
            WHERE boot_id = :boot_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'boot_id' => $boot_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Voegt een nieuwe boot toe
    // Deze functie gebruiken we straks bij boot_toevoegen.php
    public function create($klant_id, $naam, $merk)
    {
        $sql = "
            INSERT INTO boot (klant_id, naam, merk)
            VALUES (:klant_id, :naam, :merk)
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'klant_id' => $klant_id,
            'naam' => $naam,
            'merk' => $merk
        ]);
    }

    // Wijzigt een bestaande boot
    // Deze functie gebruiken we straks bij boot_wijzigen.php
    public function update($boot_id, $klant_id, $naam, $merk)
    {
        $sql = "
            UPDATE boot
            SET 
                klant_id = :klant_id,
                naam = :naam,
                merk = :merk
            WHERE boot_id = :boot_id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'boot_id' => $boot_id,
            'klant_id' => $klant_id,
            'naam' => $naam,
            'merk' => $merk
        ]);
    }

    // Verwijdert een boot uit de database
public function delete($boot_id)
{
    $sql = "DELETE FROM boot WHERE boot_id = :boot_id";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        'boot_id' => $boot_id
    ]);
}
}