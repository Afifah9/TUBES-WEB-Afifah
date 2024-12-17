<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $new_password = md5($_POST['new_password']); // Hash password baru dengan MD5

    // Cari pengguna berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Update password baru
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
        $stmt->execute(['password' => $new_password, 'username' => $username]);

        $message = "Password Anda telah berhasil direset. Anda bisa login menggunakan password baru.";
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reset Password - Réparer</title>
</head>
<body class="forgot-password-page">
    <div class="form-container">
        <h2>Reset Password</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="new_password" placeholder="Password Baru" required>
            <button type="submit">Reset Password</button>
        </form>
        <p>Kembali ke <a href="login.php">Login</a></p>
    </div>
</body>
</html>
