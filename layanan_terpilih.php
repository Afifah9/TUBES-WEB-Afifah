<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Ambil layanan yang dipilih user
$query = "
    SELECT ls.nama_layanan, ls.deskripsi, ls.harga 
    FROM user_pilihan up
    JOIN layanan_servis ls ON up.layanan_id = ls.id
    WHERE up.username = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Terpilih</title>
</head>
<body>
    <h1>Layanan yang Anda Pilih</h1>

    <table border="1">
        <tr>
            <th>Nama Layanan</th>
            <th>Deskripsi</th>
            <th>Harga</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nama_layanan']); ?></td>
                <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                <td><?php echo number_format($row['harga'], 2, ',', '.'); ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="dashboard_user.php">Kembali</a>
</body>
</html>
