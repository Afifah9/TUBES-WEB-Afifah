<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Daftar layanan manual
$menu_layanan = [
    ['id' => 1, 'nama' => 'Kendaraan', 'deskripsi' => 'Servis untuk mobil dan motor.', 'gambar' => 'Mobil.jpg'],
    ['id' => 2, 'nama' => 'Elektronik Rumah Tangga', 'deskripsi' => 'Servis AC, kulkas, mesin cuci, dll.', 'gambar' => 'Perabot.jpg'],
    ['id' => 3, 'nama' => 'Handphone', 'deskripsi' => 'Servis untuk smartphone berbagai merek.', 'gambar' => 'Handphone.jpg'],
    ['id' => 4, 'nama' => 'Laptop', 'deskripsi' => 'Servis dan perbaikan laptop.', 'gambar' => 'Laptop.jpg'],
];

// Simpan pilihan user jika formulir dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $layanan_id = $_POST['layanan_id'];

    // Masukkan data ke tabel user_pilihan
    $stmt = $conn->prepare("INSERT INTO user_pilihan (username, layanan_id) VALUES (?, ?)");
    $stmt->bind_param("si", $username, $layanan_id);
    $stmt->execute();

    $success = "Layanan berhasil dipilih!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <style>
        .layanan-container { display: flex; justify-content: space-around; flex-wrap: wrap; }
        .layanan-card { border: 1px solid #ddd; padding: 20px; text-align: center; width: 200px; border-radius: 10px; margin: 10px; }
        img { width: 100px; height: 100px; }
    </style>
</head>
<body>
    <!-- Judul dan sambutan -->
    <h1>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>

    <!-- Pesan Sukses -->
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <!-- Pilih Layanan -->
    <h2>Pilih Layanan Servis</h2>
    <form action="" method="POST">
        <div class="layanan-container">
            <?php foreach ($menu_layanan as $layanan): ?>
                <div class="layanan-card">
                    <img src="Gambar/<?php echo $layanan['gambar']; ?>" alt="<?php echo $layanan['nama']; ?>">
                    <h3><?php echo $layanan['nama']; ?></h3>
                    <p><?php echo $layanan['deskripsi']; ?></p>
                    <input type="radio" name="layanan_id" value="<?php echo $layanan['id']; ?>" required> Pilih
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit">Konfirmasi Pilihan</button>
    </form>

    <!-- Link ke halaman layanan terpilih -->
    <br>
    <a href="layanan_terpilih.php">Lihat Layanan Terpilih</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
