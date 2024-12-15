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
            <a href="login.php">Login</a>
        </nav>
    </header>
    <main>
        <div class="carousel">
            <img src="Gambar/Gambar 1.jpg" alt="Slide 1">
            <img src="Gambar/Gambar 2.jpg" alt="Slide 2">
            <img src="Gambar/Gambar 3.jpg" alt="Slide 3">
            <img src="Gambar/Gambar 4.jpg" alt="Slide 4">
            <img src="Gambar/Gambar 5.jpg" alt="Slide 5">
        </div>

        <h2 style="text-align:center; margin-top: 20px;">Layanan Kami</h2>
        <div class="service-menu">
            <div class="service-item">
                <img src="Gambar/Mobil.jpg" alt="Kendaraan">
                <h3>Kendaraan</h3>
            </div>
            <div class="service-item">
                <img src="Gambar/Perabot.jpg" alt="Elektronik Rumah Tangga">
                <h3>Elektronik Rumah Tangga</h3>
            </div>
            <div class="service-item">
                <img src="Gambar/Handphone.jpg" alt="Handphone">
                <h3>Handphone</h3>
            </div>
            <div class="service-item">
                <img src="Gambar/Laptop.jpg" alt="Laptop">
                <h3>Laptop</h3>
            </div>
        </div>

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
