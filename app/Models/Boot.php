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

    // Eén boot ophalen via het boot_id
    // Deze functie gebruiken we bij boot_wijzigen.php
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
    // Deze functie gebruiken we bij boot_toevoegen.php
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

    // Wijzigt een bestaande boot volledig
    // Deze functie laten we staan, maar gebruiken we nu niet voor boot_wijzigen.php
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

    // Wijzigt alleen de naam van een boot
    // Het merk blijft bewust hetzelfde
    public function updateZonderMerk($boot_id, $naam)
    {
        $sql = "
            UPDATE boot
            SET naam = :naam
            WHERE boot_id = :boot_id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'boot_id' => $boot_id,
            'naam' => $naam
        ]);
    }

    // Controleert of een boot gekoppelde onderhoudsopdrachten heeft
    // Dit voorkomt een fatale fout bij verwijderen
    public function heeftOnderhoudsopdrachten($boot_id)
    {
        $sql = "
            SELECT COUNT(*) 
            FROM onderhoudsopdracht
            WHERE boot_id = :boot_id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'boot_id' => $boot_id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Verwijdert een boot alleen als er geen onderhoudsopdrachten aan gekoppeld zijn
    public function delete($boot_id)
    {
        if ($this->heeftOnderhoudsopdrachten($boot_id)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            DELETE FROM boot 
            WHERE boot_id = :boot_id
        ");

        return $stmt->execute([
            'boot_id' => $boot_id
        ]);
    }
}