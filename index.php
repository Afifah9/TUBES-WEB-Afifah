<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réparer</title>
    <link rel="stylesheet" href="style.css"> <!-- Link ke file CSS -->
</head>
<body class="index-page">
    <!-- Loader -->
    <div class="loader">
        <img src="images/logo.png" alt="Logo Réparer">
        <div class="light-animation"></div>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <header>
            <nav class="navbar">
                <div class="navbar-left">
                    <img src="images/logo.png" alt="Logo Réparer" class="navbar-logo">
                    <div class="navbar-brand">Réparer</div>
                </div>
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="index.php">Beranda</a></li>
                    <li class="nav-item"><a href="about.php">Tentang Kami</a></li>
                    <li class="nav-item"><a href="services.php">Layanan</a></li>
                    <li class="nav-item"><a href="contact.php">Kontak</a></li>
                    <li class="nav-item"><a href="login.php" class="login-button">Login</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="hero">
                <h1>Selamat Datang di Réparer</h1>
                <p>Réparer adalah platform penyedia layanan perbaikan terbaik untuk kebutuhan Anda. Kami menawarkan solusi yang efektif dan efisien untuk berbagai jenis kerusakan, mulai dari elektronik, perabotan, hingga kendaraan.</p>
            </section>

            <section class="photos">
                <div class="photo" style="background-image: url('images/photo1.jpg');"></div>
                <div class="photo" style="background-image: url('images/photo2.jpg');"></div>
                <div class="photo" style="background-image: url('images/photo3.jpg');"></div>
            </section>
        </main>

        <?php include 'footer.php'; ?>
    </div>

    <script>
        // Animasi loader selama 1.3 detik
        setTimeout(function () {
            document.querySelector('.loader').style.display = 'none';
            document.querySelector('.content').style.display = 'block';
        }, 1300);
    </script>
</body>

</html>
