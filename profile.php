<?php
session_start();

// Include file koneksi database (pastikan koneksi PDO sudah benar)
include('db.php');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data user dari database menggunakan PDO
$userId = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :userId";  // Menggunakan parameterized query
$stmt = $pdo->prepare($query);
$stmt->execute(['userId' => $userId]);
$user = $stmt->fetch();  // Mengambil data user sebagai array asosiatif

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah file gambar diupload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];

        // Ekstensi file yang diperbolehkan
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            // Tentukan lokasi penyimpanan file
            $newFileName = 'profile_' . uniqid() . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            // Pindahkan file yang diupload ke direktori tujuan
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Simpan path file di database
                $query = "UPDATE users SET profile_picture = :profile_picture WHERE id = :user_id";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'profile_picture' => $newFileName,
                    'user_id' => $userId
                ]);

                echo "File berhasil di-upload dan disimpan.";
            } else {
                echo "Terjadi kesalahan saat memindahkan file.";
            }
        } else {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
        }
    } else {
        echo "Tidak ada file yang diupload atau terjadi kesalahan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-image">
                <!-- Menampilkan foto profil jika ada -->
                <img src="./uploads/<?php echo $user['profile_picture'] ?? 'default.png'; ?>" alt="Foto Profil">
            </div>
            <div class="profile-info">
                <h1><?php echo $user['username']; ?></h1>
                <p>Email: <?php echo $user['email'] ?? 'Belum diatur'; ?></p>
                <p>Alamat: <?php echo $user['address'] ?? 'Belum diatur'; ?></p>
                <p>Bergabung sejak: <?php echo $user['created_at']; ?></p>
            </div>
        </div>

        <!-- Form untuk mengedit profil -->
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat"><?php echo $user['alamat'] ?? ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="profile_picture">Ganti Foto Profil</label>
                <input type="file" id="profile_picture" name="profile_picture">
                <!-- Tombol Simpan -->
                <button type="submit" class="save-btn">Simpan Perubahan</button>
            </div>
        </form>
        <!-- Tombol Logout -->
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <!-- Tombol Kembali -->
        <?php
        // Tentukan link kembali berdasarkan role pengguna
        $back_link = ($_SESSION['role'] === 'admin') ? 'dashboard_admin.php' : 'dashboard_user.php';
        ?>
        <form action="<?php echo $back_link; ?>" method="get">
            <button type="submit" class="back-btn">Kembali</button>
        </form>


    </div>
</body>

</html>