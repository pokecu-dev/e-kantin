<?php

session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    // echo $_SESSION['status'];
    header("location: ../index.php");
    exit();
}
if ($_SESSION['role'] != 'ADMIN') {
    header("location: ../index.php");
}



$nama = $_SESSION['nama_lengkap'];

// echo $nama . '<br> <br>';


// echo ' sebagai pembeli';



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <header class="main-header">
            <div class="burger-menu" id="burger-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>
            <div class="logo">Canteen Admin</div>
            <nav class="nav-menu">
                <a href="#" class="active">Beranda</a>
                <a href="#">Edit</a>
                <a href="#">Profil</a>
            </nav>
            <div class="user-profile">
                <i class="fa-regular fa-bell"></i>
                <div class="avatar"></div>
            </div>
        </header>

        <main class="content">
            <section class="summary-section">
                <h1>Dashboard Ringkasan</h1>
                <p class="subtitle">Selamat datang kembali, berikut status operasional kantin hari ini.</p>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="icon-box blue"><i class="fa-solid fa-store"></i></div>
                        <div class="stat-info">
                            <span>TOTAL KANTIN</span>
                            <h2>24</h2>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-box orange"><i class="fa-solid fa-utensils"></i></div>
                        <div class="stat-info">
                            <span>TOTAL MENU</span>
                            <h2>158</h2>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-box green"><i class="fa-solid fa-chart-line"></i></div>
                        <div class="stat-info">
                            <span>TOTAL PENJUALAN</span>
                            <h2>42</h2>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-box light-blue"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-info">
                            <span>TOTAL PEMBELI</span>
                            <h2>1,204</h2>
                        </div>
                    </div>
                </div>
            </section>

            <section class="main-grid">
                <div class="orders-container">
                    <div class="section-header">
                        <h3>Pesanan Terbaru</h3>
                        <a href="#" class="view-all">LIHAT SEMUA</a>
                    </div>

                    <div class="order-item">
                        <div class="order-icon blue"><i class="fa-solid fa-bag-shopping"></i></div>
                        <div class="order-details">
                            <span class="order-id">Order #CK-9021</span>
                            <span class="order-desc">Nasi Goreng Spesial • Kantin A</span>
                        </div>
                        <div class="order-price">
                            <span class="price">Rp 25.000</span>
                            <span class="status confirm">CONFIRMED</span>
                        </div>
                    </div>

                    <div class="order-item">
                        <div class="order-icon green"><i class="fa-solid fa-mug-hot"></i></div>
                        <div class="order-details">
                            <span class="order-id">Order #CK-9020</span>
                            <span class="order-desc">Es Teh Manis • Kantin B</span>
                        </div>
                        <div class="order-price">
                            <span class="price">Rp 5.000</span>
                            <span class="status confirm">CONFIRMED</span>
                        </div>
                    </div>
                </div>

                <div class="target-card">
                    <h3>Target Bulanan</h3>
                    <p>Pertumbuhan transaksi mencapai 72% dari target.</p>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 72%;"></div>
                    </div>
                    <div class="progress-labels">
                        <span>72% Selesai</span>
                        <span>Target: 5k Order</span>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
<!-- <label>
        <input type="checkbox">
        <div class="toggle">
            <span class="top-line common"></span>
            <span class="middle-line common"></span>
            <span class="bottom-line common"></span>
        </div>
        <div class="slide">
            <h1>menu</h1>
            <li><a href="#">dashboard</a></li>
            <li><a href="#">dashboard</a></li>
            <li><a href="#">dashboard</a></li>
            <li><a href="#">dashboard</a></li>
            <li><a href="#">dashboard</a></li>
            <li><a href="#">dashboard</a></li>
        </div>
    </label> -->

<h2>tombol log out</h2>
<!-- <a href="/logout.php"><button>log out</button></a> -->
<a href="./../logout.php"><button>log out</button></a>

<br>
<p>tes up file</p>
<form action="upfile.php" method="post" enctype="multipart/form-data">
    <label for="myfile">pilih file:</label>
    <input type="file" id="myfile" name="filename">
    <input type="submit" value="unggah">
</form>

<br>
<a href="TESTINGFITUR.php">tes WILAYAH TESTING FITUR >:[]</a>

</body>

</html>