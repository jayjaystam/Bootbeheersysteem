<?php

require_once '../config/database.php';

$database = new Database();
$pdo = $database->connect();

echo 'Databaseverbinding werkt!';
