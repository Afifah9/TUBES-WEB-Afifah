<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_layanan = $_POST['nama_layanan'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO services (nama_layanan, kategori, deskripsi) VALUES ('$nama_layanan', '$kategori', '$deskripsi')";
    $conn->query($query);
    header('Location: crud_services.php');
}

$query = "SELECT * FROM services";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Kelola Layanan</title>
</head>
<body>
    <h1>Kelola Layanan</h1>
    <form method="POST">
        <label>Nama Layanan:</label>
        <input type="text" name="nama_layanan" required>
        <label>Kategori:</label>
        <input type="text" name="kategori" required>
        <label>Deskripsi:</label>
        <textarea name="deskripsi" required></textarea>
        <button type="submit">Tambah</button>
    </form>
    <h2>Daftar Layanan</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
        </tr>
        <?php while ($service = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $service['nama_layanan']; ?></td>
                <td><?php echo $service['kategori']; ?></td>
                <td><?php echo $service['deskripsi']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
