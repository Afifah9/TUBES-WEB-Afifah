<?php
session_start();
require 'koneksi.php';

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
    <title>Pilih Tempat Servis</title>
</head>
<body>
    <header>
        <h1>Selamat Datang di Website Pilihan Tempat Servis</h1>
        <nav>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php">Logout</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="crud_services.php">Kelola Layanan</a>
                    <a href="crud_locations.php">Kelola Tempat Servis</a>
                <?php else: ?>
                    <a href="dashboard_user.php">Dashboard</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
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
    </main>
</body>
</html>