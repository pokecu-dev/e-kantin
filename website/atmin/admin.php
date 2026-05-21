<?php

require_once __DIR__ . "/../include/koneksi.php";

// ======================
// TOTAL PRODUK
// ======================

$totalProduk = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM tb_menu
");

$dataProduk = mysqli_fetch_assoc($totalProduk);

// ======================
// TOTAL USER
// ======================

$totalUser = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM users
");

$dataUser = mysqli_fetch_assoc($totalUser);

// ======================
// TOTAL OUTLET
// ======================

$totalOutlet = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM list_kantin
");

$dataOutlet = mysqli_fetch_assoc($totalOutlet);

// ======================
// PRODUK HABIS
// ======================

$produkHabis = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM tb_menu
    WHERE STOK = 0
");

$dataHabis = mysqli_fetch_assoc($produkHabis);

// ======================
// TRANSAKSI TERBARU
// ======================

$transaksi = mysqli_query($conn, "
    SELECT * 
    FROM transaksi
    ORDER BY ID_TRANSAKSI DESC
    LIMIT 5
");

// ======================
// PRODUK TERLARIS
// ======================

$terlaris = mysqli_query($conn, "
    SELECT 
        NAMA_MENU,
        RATING
    FROM tb_menu
    ORDER BY RATING DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

<link rel="stylesheet" href="style.css">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    background:#f8fafc;
    color:#1e293b;
}

/* =======================
CONTAINER
======================= */

.container{
    width:100%;
    max-width:1400px;
    margin:auto;
    padding:24px;
    margin-top:70px;
}

/* =======================
HEADER
======================= */

.dashboard-header{
    margin-bottom:30px;
}

.dashboard-header h1{
    font-size:32px;
    margin-bottom:10px;
}

.dashboard-header p{
    color:#64748b;
}

/* =======================
STATS
======================= */

.stats-scroll{
    overflow-x:auto;
    padding-bottom:10px;
}

.stats-scroll::-webkit-scrollbar{
    height:6px;
}

.stats-scroll::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:999px;
}

.stats-grid{
    display:flex;
    gap:20px;
    min-width:max-content;
}

.stat-card{
    width:260px;
    background:white;
    border-radius:20px;
    padding:24px;
    border:1px solid #e2e8f0;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

.icon-box{
    width:60px;
    height:60px;
    border-radius:16px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:28px;
    margin-bottom:18px;
}

.orange{
    background:#fff7ed;
}

.red{
    background:#fef2f2;
}

.green{
    background:#f0fdf4;
}

.blue{
    background:#eff6ff;
}

.stat-card span{
    color:#64748b;
    font-size:14px;
}

.stat-card h2{
    margin-top:10px;
    font-size:34px;
}

/* =======================
GRID
======================= */

.dashboard-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:24px;
    margin-top:30px;
}

/* =======================
CARD
======================= */

.card{
    background:white;
    border-radius:20px;
    border:1px solid #e2e8f0;
    padding:24px;
}

.card h3{
    margin-bottom:20px;
}

/* =======================
TABLE
======================= */

.table{
    width:100%;
}

.table-row{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    padding:14px 0;
    border-bottom:1px solid #f1f5f9;
}

.table-header{
    font-weight:700;
    color:#64748b;
}

/* =======================
PRODUK HABIS
======================= */

.habis-item{
    background:#fef2f2;
    color:#dc2626;
    padding:14px;
    border-radius:14px;
    margin-bottom:12px;
    font-weight:600;
}

/* =======================
RESPONSIVE
======================= */

@media(max-width:900px){

    .dashboard-grid{
        grid-template-columns:1fr;
    }

}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">

        <div class="logo">
            <img src="../../source/icon/logo1.svg" alt="">
        </div>

        <ul class="nav-links">
            <li><a href="admin.php" class="active">Beranda</a></li>
            <li><a href="akun.php">Akun</a></li>
            <li><a href="menu.php">Produk</a></li>
            <li><a href="oulet.php">Outlet</a></li>
            <li><a href="./../logout.php">Log Out</a></li>
        </ul>

    </div>
</nav>

<div class="container">

    <!-- HEADER -->

    <div class="dashboard-header">
        <h1>Dashboard Admin</h1>
        <p>Pantau semua aktivitas kantin secara real-time.</p>
    </div>

    <!-- STATS -->

    <div class="stats-scroll">

        <div class="stats-grid">

            <div class="stat-card">
                <div class="icon-box orange">📦</div>
                <span>Total Produk</span>
                <h2><?= $dataProduk['total'] ?></h2>
            </div>

            <div class="stat-card">
                <div class="icon-box blue">👤</div>
                <span>Total User</span>
                <h2><?= $dataUser['total'] ?></h2>
            </div>

            <div class="stat-card">
                <div class="icon-box green">🏪</div>
                <span>Total Outlet</span>
                <h2><?= $dataOutlet['total'] ?></h2>
            </div>

            <div class="stat-card">
                <div class="icon-box red">⚠️</div>
                <span>Produk Habis</span>
                <h2><?= $dataHabis['total'] ?></h2>
            </div>

        </div>

    </div>

    <!-- GRID -->

    <div class="dashboard-grid">

        <!-- TRANSAKSI -->

        <div class="card">

            <h3>Transaksi Terbaru</h3>

            <div class="table">

                <div class="table-row table-header">
                    <div>ID</div>
                    <div>Total</div>
                    <div>Status</div>
                </div>

                <?php while($trx = mysqli_fetch_assoc($transaksi)): ?>

                <div class="table-row">
                    <div>#<?= $trx['ID_TRANSAKSI'] ?></div>
                    <div>Rp <?= number_format($trx['TOTAL']) ?></div>
                    <div><?= $trx['STATUS'] ?></div>
                </div>

                <?php endwhile; ?>

            </div>

        </div>

        <!-- SIDEBAR -->

        <div>

            <!-- PRODUK TERLARIS -->

            <div class="card" style="margin-bottom:24px;">

                <h3>Produk Rating Tertinggi</h3>

                <?php while($top = mysqli_fetch_assoc($terlaris)): ?>

                <div class="habis-item"
                    style="
                    background:#fff7ed;
                    color:#ea580c;
                    ">
                    ⭐ <?= $top['NAMA_MENU'] ?>
                    (<?= $top['RATING'] ?>)
                </div>

                <?php endwhile; ?>

            </div>

            <!-- QUICK ACTION -->

            <div class="card">

                <h3>Quick Action</h3>

                <div style="
                    display:flex;
                    flex-direction:column;
                    gap:14px;
                ">

                    <a href="menu.php"
                    style="
                    text-decoration:none;
                    background:#f47b20;
                    color:white;
                    padding:14px;
                    border-radius:14px;
                    text-align:center;
                    font-weight:600;
                    ">
                        + Tambah Produk
                    </a>

                    <a href="akun.php"
                    style="
                    text-decoration:none;
                    background:#1e293b;
                    color:white;
                    padding:14px;
                    border-radius:14px;
                    text-align:center;
                    font-weight:600;
                    ">
                        + Tambah User
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>