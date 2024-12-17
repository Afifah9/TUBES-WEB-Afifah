<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

$success_message = '';

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? md5($_POST['password']) : $user['password'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    // Proses upload foto
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $filename = basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . time() . "_" . $filename;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
            $profile_picture = $target_file;
        } else {
            $success_message = "Format gambar tidak valid.";
        }
    } else {
        $profile_picture = $user['profile_picture'];
    }

    // Update data
    $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password, email = :email, address = :address, profile_picture = :profile_picture WHERE id = :id");
    $stmt->execute([
        'username' => $username,
        'password' => $password,
        'email' => $email,
        'address' => $address,
        'profile_picture' => $profile_picture,
        'id' => $user_id
    ]);

    $success_message = "Profil berhasil diperbarui.";
    $user = array_merge($user, [
        'username' => $username,
        'email' => $email,
        'address' => $address,
        'profile_picture' => $profile_picture
    ]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'uploads/default.png'); ?>" alt="Foto Profil">
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <p>Email: <?php echo htmlspecialchars($user['email'] ?: 'Belum diatur'); ?></p>
                <p>Alamat: <?php echo htmlspecialchars($user['address'] ?: 'Belum diatur'); ?></p>
            </div>
        </div>

        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <div class="profile-details">
            <form method="POST" enctype="multipart/form-data">
                <div class="detail">
                    <h3>Foto Profil</h3>
                    <input type="file" name="profile_picture" accept="image/*">
                </div>

                <div class="detail">
                    <h3>Username</h3>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="detail">
                    <h3>Email</h3>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="detail">
                    <h3>Password Baru</h3>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>

                <div class="detail">
                    <h3>Alamat</h3>
                    <textarea name="address" rows="4"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <a href="logout.php" class="edit-profile-btn">Logout</a>
                <button type="submit" class="edit-profile-btn">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
