<?php

class Onderhoudsopdracht
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Nieuwe onderhoudsopdracht aanmaken
    public function create($boot_id, $beschrijving, $geplande_datum)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO onderhoudsopdracht 
                (boot_id, beschrijving, status, geplande_datum, afgerond_datum)
            VALUES 
                (:boot_id, :beschrijving, :status, :geplande_datum, NULL)
        ");

        return $stmt->execute([
            'boot_id' => $boot_id,
            'beschrijving' => $beschrijving,
            'status' => 'openstaand',
            'geplande_datum' => $geplande_datum
        ]);
    }


    // Openstaande onderhoudsopdrachten ophalen met boot- en klantgegevens
    public function getOpenstaandWithBootEnKlant()
    {
        $stmt = $this->pdo->prepare("
            SELECT
                o.opdracht_id,
                o.beschrijving,
                o.status,
                o.geplande_datum,
                b.naam AS bootnaam,
                b.merk,
                k.naam AS klantnaam,
                k.email,
                k.telefoonnummer
            FROM onderhoudsopdracht o
            INNER JOIN boot b ON o.boot_id = b.boot_id
            INNER JOIN klant k ON b.klant_id = k.klant_id
            WHERE o.status = 'openstaand'
            ORDER BY o.geplande_datum ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markeerAlsAfgerond($opdracht_id)
    {
    $stmt = $this->pdo->prepare("
        UPDATE onderhoudsopdracht
        SET 
            status = 'afgerond',
            afgerond_datum = CURDATE()
        WHERE opdracht_id = :opdracht_id
        AND status = 'openstaand'
    ");

    return $stmt->execute([
        'opdracht_id' => $opdracht_id
    ]);
    }

    
    
    // Alle afgeronde onderhoudsopdrachten ophalen met boot- en klantgegevens
public function getAfgerondWithBootEnKlant()
{
    $stmt = $this->pdo->prepare("
        SELECT 
            o.opdracht_id,
            o.beschrijving,
            o.status,
            o.geplande_datum,
            o.afgerond_datum,
            b.boot_id,
            b.naam AS bootnaam,
            b.merk,
            k.naam AS klantnaam
        FROM onderhoudsopdracht o
        INNER JOIN boot b ON o.boot_id = b.boot_id
        INNER JOIN klant k ON b.klant_id = k.klant_id
        WHERE o.status = 'afgerond'
        ORDER BY o.afgerond_datum DESC
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Afgeronde onderhoudsopdrachten ophalen van één specifieke boot
public function getAfgerondByBootId($boot_id)
{
    $stmt = $this->pdo->prepare("
        SELECT 
            o.opdracht_id,
            o.beschrijving,
            o.status,
            o.geplande_datum,
            o.afgerond_datum,
            b.boot_id,
            b.naam AS bootnaam,
            b.merk,
            k.naam AS klantnaam
        FROM onderhoudsopdracht o
        INNER JOIN boot b ON o.boot_id = b.boot_id
        INNER JOIN klant k ON b.klant_id = k.klant_id
        WHERE o.status = 'afgerond'
        AND o.boot_id = :boot_id
        ORDER BY o.afgerond_datum DESC
    ");

    $stmt->execute([
        'boot_id' => $boot_id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}