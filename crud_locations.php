<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_tempat = $_POST['nama_tempat'];
    $alamat = $_POST['alamat'];
    $kontak = $_POST['kontak'];

    $query = "INSERT INTO locations (nama_tempat, alamat, kontak) VALUES ('$nama_tempat', '$alamat', '$kontak')";
    $conn->query($query);
    header('Location: crud_locations.php');
}

$query = "SELECT * FROM locations";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Kelola Tempat Servis</title>
</head>
<body>
    <h1>Kelola Tempat Servis</h1>
    <form method="POST">
        <label>Nama Tempat:</label>
        <input type="text" name="nama_tempat" required>
        <label>Alamat:</label>
        <input type="text" name="alamat" required>
        <label>Kontak:</label>
        <input type="text" name="kontak" required>
        <button type="submit">Tambah</button>
    </form>
    <h2>Daftar Tempat Servis</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Kontak</th>
        </tr>
        <?php while ($location = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $location['nama_tempat']; ?></td>
                <td><?php echo $location['alamat']; ?></td>
                <td><?php echo $location['kontak']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
