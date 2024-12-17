<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses pemesanan layanan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, service_id, status) VALUES (:user_id, :service_id, 'pending')");
    $stmt->execute(['user_id' => $user_id, 'service_id' => $service_id]);
    $message = "Pesanan Anda telah diterima. Kami akan segera menghubungi Anda.";
}

// Ambil daftar layanan
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll();

// Ambil daftar pesanan pengguna
$stmt = $pdo->prepare("SELECT orders.id, services.name, orders.status 
                       FROM orders 
                       JOIN services ON orders.service_id = services.id 
                       WHERE orders.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">
    <title>Dashboard User - Réparer</title>
</head>

<body class="dashboard-user">
    <div class="dashboard-container" style="position: relative;">
        <div class="logout-container">
            <a href="profile.php" class="profile-link">Profil Saya</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>


        <h2>Dashboard User</h2>

        <h3>Pesan Layanan</h3>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <form method="POST">
            <select name="service_id" required>
                <option value="">Pilih Layanan</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo $service['id']; ?>">
                        <?php echo htmlspecialchars($service['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Pesan</button>
        </form>

        <h3>Pesanan Anda</h3>
        <table>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama Layanan</th>
                <th>Status</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <!-- Tombol Kembali -->
        <button type="button" class="back-btn" onclick="window.location.href='login.php';">Kembali</button>

    </div>
</body>

</html>