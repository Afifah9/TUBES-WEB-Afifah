<?php
$host = 'localhost';
$username = 'root';
$password = 'afifah123';
$database = 'web_dinamis';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Koneksi Gagal: ' . $conn->connect_error);
}
?>