<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM locations WHERE id = $id";
        $conn->query($query);
    } else {
        $id = $_POST['id'] ?? null;
        $nama_tempat = $_POST['nama_tempat'];
        $alamat = $_POST['alamat'];
        $kontak = $_POST['kontak'];

        if ($id) {
            $query = "UPDATE locations SET nama_tempat = '$nama_tempat', alamat = '$alamat', kontak = '$kontak' WHERE id = $id";
        } else {
            $query = "INSERT INTO locations (nama_tempat, alamat, kontak) VALUES ('$nama_tempat', '$alamat', '$kontak')";
        }
        $conn->query($query);
    }
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
        <input type="hidden" name="id" id="id">
        <label>Nama Tempat:</label>
        <input type="text" name="nama_tempat" id="nama_tempat" required>
        <label>Alamat:</label>
        <input type="text" name="alamat" id="alamat" required>
        <label>Kontak:</label>
        <input type="text" name="kontak" id="kontak" required>
        <button type="submit">Simpan</button>
    </form>
    <h2>Daftar Tempat Servis</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Kontak</th>
            <th>Aksi</th>
        </tr>
        <?php while ($location = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $location['nama_tempat']; ?></td>
                <td><?php echo $location['alamat']; ?></td>
                <td><?php echo $location['kontak']; ?></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $location['id']; ?>">
                        <button type="button" onclick="editLocation(<?php echo $location['id']; ?>, '<?php echo $location['nama_tempat']; ?>', '<?php echo $location['alamat']; ?>', '<?php echo $location['kontak']; ?>')">Edit</button>
                        <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <script>
        function editLocation(id, nama, alamat, kontak) {
            document.getElementById('id').value = id;
            document.getElementById('nama_tempat').value = nama;
            document.getElementById('alamat').value = alamat;
            document.getElementById('kontak').value = kontak;
        }
    </script>
</body>
</html>
