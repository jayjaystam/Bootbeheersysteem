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
}