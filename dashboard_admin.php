<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle CRUD operations for services
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create_service') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = 'images/';
    $upload_file = $upload_dir . basename($image);

    if (move_uploaded_file($tmp_name, $upload_file)) {
        $stmt = $pdo->prepare("INSERT INTO services (name, description, image) VALUES (:name, :description, :image)");
        $stmt->execute(['name' => $name, 'description' => $description, 'image' => $image]);
        echo "Layanan baru berhasil ditambahkan.";
    } else {
        echo "Terjadi kesalahan saat mengunggah gambar.";
    }
}

// Edit layanan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_service') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'] ?? null;

    if ($image) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = 'images/';
        $upload_file = $upload_dir . basename($image);
        move_uploaded_file($tmp_name, $upload_file);
        $stmt = $pdo->prepare("UPDATE services SET name = :name, description = :description, image = :image WHERE id = :id");
        $stmt->execute(['name' => $name, 'description' => $description, 'image' => $image, 'id' => $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE services SET name = :name, description = :description WHERE id = :id");
        $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
    }
    echo "Layanan berhasil diperbarui.";
}

// Hapus layanan
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_service') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo "Layanan berhasil dihapus.";
}

// Fetch services
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll();

// Fetch users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'confirm_order') {
    $order_id = $_POST['id'];
    $new_status = 'Confirmed'; // Ubah sesuai nilai ENUM yang diperbolehkan

    // Query UPDATE untuk mengubah status pesanan
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $new_status, 'id' => $order_id]);

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Status pesanan berhasil dikonfirmasi!'); window.location.reload();</script>";
    } else {
        echo "<script>alert('Gagal mengubah status pesanan.');</script>";
    }
}

// Fetch orders
$stmt = $pdo->query("SELECT o.id, u.username, s.name as service_name, o.status 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     JOIN services s ON o.service_id = s.id");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">
    <title>Dashboard Admin - Réparer</title>
</head>

<body class="dashboard-admin">
    <div class="dashboard-container">
        <div class="logout-container">
            <a href="profile.php" class="profile-link">Profil Saya</a>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>

        <h2>Dashboard Admin</h2>

        <!-- CRUD Services -->
        <h3>Manage Services</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Nama Layanan" required>
            <textarea name="description" placeholder="Deskripsi Layanan" required></textarea>
            <input type="file" name="image" required>
            <input type="hidden" name="action" value="create_service">
            <button type="submit">Tambah Layanan</button>
        </form>

        <h3>Daftar Layanan</h3>
        <table>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['name']); ?></td>
                    <td><?php echo htmlspecialchars($service['description']); ?></td>
                    <td><img src="images/<?php echo htmlspecialchars($service['image']); ?>" alt="Layanan"></td>
                    <td>
                        <form method="POST" enctype="multipart/form-data" style="display:inline;">
                            <input type="text" name="name" placeholder="Nama" value="<?php echo $service['name']; ?>" required>
                            <textarea name="description" placeholder="Deskripsi"><?php echo $service['description']; ?></textarea>
                            <input type="file" name="image">
                            <input type="hidden" name="action" value="edit_service">
                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                            <button type="submit">Edit</button>
                        </form>
                        <a href="?action=delete_service&id=<?php echo $service['id']; ?>" onclick="return confirm('Hapus layanan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- CRUD Users -->
        <h3>Manage Users</h3>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <input type="hidden" name="action" value="add_user">
            <button type="submit">Tambah Pengguna</button>
        </form>

        <h3>Daftar Pengguna</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <a href="?action=delete_user&id=<?php echo $user['id']; ?>" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Orders Management -->
        <h3>Riwayat Pesanan</h3>
        <table>
            <tr>
                <th>ID Pesanan</th>
                <th>Username</th>
                <th>Layanan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['username']; ?></td>
                    <td><?php echo $order['service_name']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="action" value="confirm_order">
                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                            <button type="submit">Konfirmasi</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>
