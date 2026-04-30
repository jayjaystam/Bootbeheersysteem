-- Database structuur voor het bootbeheersysteem
CREATE DATABASE IF NOT EXISTS bootbeheersysteem
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE bootbeheersysteem;

CREATE TABLE gebruiker (
    gebruiker_id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    gebruikersnaam VARCHAR(50) NOT NULL UNIQUE,
    wachtwoord_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL
);

CREATE TABLE klant (
    klant_id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefoonnummer VARCHAR(20) NOT NULL
);

CREATE TABLE boot (
    boot_id INT AUTO_INCREMENT PRIMARY KEY,
    klant_id INT NOT NULL,
    naam VARCHAR(100) NOT NULL,
    merk VARCHAR(100) NOT NULL,

    CONSTRAINT fk_boot_klant
    FOREIGN KEY (klant_id)
    REFERENCES klant(klant_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

CREATE TABLE onderhoudsopdracht (
    opdracht_id INT AUTO_INCREMENT PRIMARY KEY,
    boot_id INT NOT NULL,
    beschrijving TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'openstaand',
    geplande_datum DATE NOT NULL,
    afgerond_datum DATE NULL,

    CONSTRAINT fk_onderhoudsopdracht_boot
    FOREIGN KEY (boot_id)
    REFERENCES boot(boot_id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);