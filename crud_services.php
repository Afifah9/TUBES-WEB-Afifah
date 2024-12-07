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
        $query = "DELETE FROM services WHERE id = $id";
        $conn->query($query);
    } else {
        $id = $_POST['id'] ?? null;
        $nama_layanan = $_POST['nama_layanan'];
        $kategori = $_POST['kategori'];
        $deskripsi = $_POST['deskripsi'];

        if ($id) {
            $query = "UPDATE services SET nama_layanan = '$nama_layanan', kategori = '$kategori', deskripsi = '$deskripsi' WHERE id = $id";
        } else {
            $query = "INSERT INTO services (nama_layanan, kategori, deskripsi) VALUES ('$nama_layanan', '$kategori', '$deskripsi')";
        }
        $conn->query($query);
    }
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
        <input type="hidden" name="id" id="id">
        <label>Nama Layanan:</label>
        <input type="text" name="nama_layanan" id="nama_layanan" required>
        <label>Kategori:</label>
        <input type="text" name="kategori" id="kategori" required>
        <label>Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" required></textarea>
        <button type="submit">Simpan</button>
    </form>
    <h2>Daftar Layanan</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
        <?php while ($service = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $service['nama_layanan']; ?></td>
                <td><?php echo $service['kategori']; ?></td>
                <td><?php echo $service['deskripsi']; ?></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                        <button type="button" onclick="editService(<?php echo $service['id']; ?>, '<?php echo $service['nama_layanan']; ?>', '<?php echo $service['kategori']; ?>', '<?php echo $service['deskripsi']; ?>')">Edit</button>
                        <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <script>
        function editService(id, nama, kategori, deskripsi) {
            document.getElementById('id').value = id;
            document.getElementById('nama_layanan').value = nama;
            document.getElementById('kategori').value = kategori;
            document.getElementById('deskripsi').value = deskripsi;
        }
    </script>
</body>
</html>
