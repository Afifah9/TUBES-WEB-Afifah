<?php
// db.php

$host = 'localhost';  // Host database
$db = 'repair_service'; // Nama database
$user = 'root'; // Username database
$pass = 'afifah123'; // Password database (sesuaikan dengan konfigurasi lokal Anda)
$charset = 'utf8mb4'; // Charset

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>