<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

$query_services = "SELECT * FROM services";
$result_services = $conn->query($query_services);

$query_locations = "SELECT * FROM locations";
$result_locations = $conn->query($query_locations);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard User</title>
</head>
<body>
    <h1>Dashboard User</h1>
    <h2>Daftar Layanan</h2>
    <ul>
        <?php while ($service = $result_services->fetch_assoc()): ?>
            <li><?php echo $service['nama_layanan']; ?> - <?php echo $service['kategori']; ?></li>
        <?php endwhile; ?>
    </ul>

    <h2>Tempat Servis</h2>
    <ul>
        <?php while ($location = $result_locations->fetch_assoc()): ?>
            <li><?php echo $location['nama_tempat']; ?> - <?php echo $location['alamat']; ?></li>
        <?php endwhile; ?>
    </ul>
    <a href="logout.php">Logout</a>
</body>
</html>