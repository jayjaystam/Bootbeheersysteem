-- Testdata voor het bootbeheersysteem
USE bootbeheersysteem;

INSERT INTO gebruiker (naam, email, gebruikersnaam, wachtwoord_hash, rol)
VALUES
(
    'Beheerder Test',
    'beheerder@test.nl',
    'beheerder',
    '$2y$12$fzP3rTOwZRGG1d4JKf7PduczyD7ifo/Iwb2ueHzRlxFGwU8Zki.CS',
    'beheerder'
),
(
    'Werknemer Test',
    'werknemer@test.nl',
    'werknemer',
    '$2y$12$YltWaNfwcSjAK0BUYw49nOQuomdvWPAbQWIDf4wSaM3A7nvkHfMwa',
    'werknemer'
);

INSERT INTO klant (naam, email, telefoonnummer)
VALUES
('Jan de Vries', 'jan.devries@test.nl', '0612345678'),
('Sophie Bakker', 'sophie.bakker@test.nl', '0623456789'),
('Peter Jansen', 'peter.jansen@test.nl', '0634567890');

INSERT INTO boot (klant_id, naam, merk)
VALUES
(1, 'De Zeemeeuw', 'Yamaha'),
(1, 'Waterloper', 'Honda Marine'),
(2, 'Blauwe Golf', 'Suzuki Marine'),
(3, 'Noordster', 'Mercury');

INSERT INTO onderhoudsopdracht (boot_id, beschrijving, status, geplande_datum, afgerond_datum)
VALUES
(1, 'Motor controleren en olie verversen.', 'openstaand', '2026-05-06', NULL),
(2, 'Schroef controleren en romp schoonmaken.', 'openstaand', '2026-05-08', NULL),
(3, 'Accu vervangen en verlichting testen.', 'afgerond', '2026-04-20', '2026-04-21'),
(4, 'Algemene onderhoudscontrole uitvoeren.', 'openstaand', '2026-05-10', NULL);