<?php
require_once __DIR__ . "/../include/koneksi.php";

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// =====================
// AMBIL MENU
// =====================
$sql = "SELECT * FROM tb_menu";
$query = $conn->query($sql);

// =====================
// TOTAL PRODUK
// =====================
$sql_total = "SELECT COUNT(*) AS total FROM tb_menu";
$total_produk = $conn->query($sql_total)->fetch_assoc()['total'] ?? 0;

// =====================
// STOK RENDAH
// =====================
$sql_low = "SELECT COUNT(*) AS low_stock FROM tb_menu WHERE STOK <= 5";
$low_stock = $conn->query($sql_low)->fetch_assoc()['low_stock'] ?? 0;

// =====================
// PRODUK TERSEDIA
// =====================
$sql_ready = "SELECT COUNT(*) AS ready FROM tb_menu WHERE STATUS = 'tersedia'";
$ready = $conn->query($sql_ready)->fetch_assoc()['ready'] ?? 0;

// =====================
// RATING RATA-RATA
// =====================
$sql_rating = "SELECT AVG(RATING) AS avg_rating FROM tb_menu";
$avg_rating = $conn->query($sql_rating)->fetch_assoc()['avg_rating'] ?? 0;
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #f36f20;
            --bg-gray: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --white: #ffffff;

            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.05);
            --radius: 18px;

            --col-product: 2fr;
            --col-category: 1fr;
            --col-price: 1fr;
            --col-stock: 1fr;
            --col-status: .7fr;
            --col-action: .5fr;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gray);
            color: var(--text-dark);
            line-height: 1.5;
        }

        /* =========================
NAVBAR
========================= */

        .nav-links a {
            text-decoration: none;
            color: #888;
            /* Warna abu-abu */
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a.active {
            color: var(--primary);
            border-bottom: 2px solid #F47B20;
            padding-bottom: 5px;
        }



        /* =========================
   CONTAINER
========================= */

        .container {
            width: 100%;
            max-width: 1400px;
            margin-inline: auto;
            padding: 24px;
            margin-top: 60px;
        }

        /* =========================
   HEADER
========================= */

        .header {
            margin-bottom: 30px;
        }

        .header-title {
            width: 100%;
        }

        .header-title h1 {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            margin-bottom: 10px;
        }

        .header-title p {
            color: var(--text-muted);
            margin-top: 10px;
        }

        /* =========================
   SEARCH
========================= */

        .input-group {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;

            background: #fff7ed;
            padding: 18px;
            border-radius: var(--radius);
            border: 1px solid #fed7aa;
        }

        .input-group input {
            flex: 1 1 300px;
            min-width: 0;

            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-color);

            font-size: 14px;
            outline: none;
        }

        .input-group input:focus {
            border-color: var(--primary-orange);
        }

        .btn-orange {
            border: none;
            background: var(--primary-orange);
            color: white;

            padding: 14px 24px;
            border-radius: 12px;

            font-weight: 600;
            cursor: pointer;

            transition: .3s;
        }

        .btn-orange:hover {
            opacity: .9;
        }

        /* =========================
   STATS
========================= */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;

            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 24px;

            display: flex;
            align-items: center;
            gap: 16px;

            box-shadow: var(--shadow-soft);
            border: 1px solid var(--border-color);
        }

        .icon-box {
            width: 56px;
            height: 56px;

            border-radius: 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
            flex-shrink: 0;
        }

        .icon-total {
            background: #fff7ed;
        }

        .icon-low {
            background: #fefaf2;
        }

        .icon-active {
            background: #fbfdf0;
        }

        .stat-info span {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .stat-info h2 {
            font-size: 26px;
        }

        /* =========================
   DATA CARD
     ========================= */

        .data-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        /* =========================
   TOOLBAR
========================= */

        .toolbar {
            padding: 20px;

            display: flex;
            flex-wrap: wrap;
            gap: 14px;

            justify-content: space-between;
            align-items: center;

            border-bottom: 1px solid var(--border-color);
        }

        .search-input {
            flex: 1 1 280px;
            min-width: 0;

            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-color);

            background: #f8fafc;
        }

        .toolbar>div {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-page {
            border: 1px solid var(--border-color);
            background: white;

            padding: 10px 16px;
            border-radius: 10px;

            cursor: pointer;
            transition: .2s;
        }

        .btn-page:hover {
            background: #f8fafc;
        }

        /* =========================
   TABLE GRID
========================= */

        .grid-wrapper {
            background-color: var(--white);
            width: 100%;
            border-bottom: 1px solid var(--border-color);
            overflow-x: auto;
        }

        .grid-header,
        .grid-row {
            min-width: 900px;

            display: grid;
            grid-template-columns:
                var(--col-product) var(--col-category) var(--col-price) var(--col-stock) var(--col-status) var(--col-action);

            gap: 16px;
            align-items: center;

            padding: 18px 20px;
        }

        .grid-header {
            background: #fafafa;

            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;

            color: var(--text-muted);

            border-bottom: 1px solid var(--border-color);
        }

        .grid-row {
            border-bottom: 1px solid var(--border-color);
            transition: .2s;
        }

        .grid-row:hover {
            background: #f8fafc;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 14px;

            min-width: 0;
        }

        .product-info strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .img-placeholder {
            width: 48px;
            height: 48px;

            border-radius: 12px;

            background: #f1f5f9;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }

        /* =========================
   BADGES
========================= */
        .badge {
            padding: 6px 12px;
            border-radius: 999px;

            font-size: 12px;
            font-weight: 600;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-gray {
            background: #e2e8f0;
            color: #475569;
        }

        /* =========================
   SWITCH
========================= */

        .switch {
            position: relative;
            width: 44px;
            height: 24px;
            display: inline-block;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            inset: 0;
            background: #cbd5e1;
            border-radius: 999px;
            cursor: pointer;
            transition: .3s;
        }

        .slider::before {
            content: "";

            position: absolute;
            width: 18px;
            height: 18px;

            left: 3px;
            top: 3px;

            background: white;
            border-radius: 50%;

            transition: .3s;
        }

        .switch input:checked+.slider {
            background: var(--primary-orange);
        }

        .switch input:checked+.slider::before {
            transform: translateX(20px);
        }

        /* =========================
   ACTIONS
========================= */

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;

            font-size: 18px;
            cursor: pointer;
        }

        /* =========================
   FOOTER
========================= */

        .footer {
            padding: 20px;

            display: flex;
            flex-wrap: wrap;
            gap: 14px;

            justify-content: space-between;
            align-items: center;

            color: var(--text-muted);
        }

        /* =========================
   MOBILE
========================= */

        @media (max-width: 768px) {

            .container {
                padding: 16px;
            }


            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar>div {
                width: 100%;
            }

            .btn-page {
                flex: 1;
            }

            .footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .input-group {
                padding: 14px;
            }

            .stat-card {
                padding: 18px;
            }

        }
    </style>
</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>

            <!-- Burger Menu (Mobile Only) -->
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <ul class="nav-links">
                <li><a href="admin.php">Beranda</a></li>
                <li><a href="akun.php">Akun</a></li>
                <li><a href="menu.php" class="active">Produk</a></li>
                <li><a href="oulet.php">Outlet</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>
    <!-- CONTAINER -->

    <div class="container">

        <!-- HEADER -->

        <div class="header">
            <div class="header-title">
                <h1>Kelola Produk</h1>
                <form action="cariProduk.php" method="GET" class="input-group">
                    <input type="text"
                        name="query"
                        placeholder="Cari Nama, ID Menu, atau ID Kantin..."
                        required>
                    <button type="submit" class="btn-orange">
                        Cari Menu
                    </button>
                </form>
                <p>
                    Pantau dan kelola inventaris produk secara real-time.
                </p>
            </div>
        </div>

        <!-- STATS -->
        <?php
        // TOTAL PRODUK
        $total_produk = $conn->query("SELECT COUNT(*) AS total FROM tb_menu")
            ->fetch_assoc()['total'] ?? 0;

        // STOK RENDAH (<=5)
        $stok_rendah = $conn->query("SELECT COUNT(*) AS total FROM tb_menu WHERE STOK <= 5")
            ->fetch_assoc()['total'] ?? 0;

        // PRODUK HABIS (STOK = 0)
        $produk_habis = $conn->query("SELECT COUNT(*) AS total FROM tb_menu WHERE STOK = 0")
            ->fetch_assoc()['total'] ?? 0;
        ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-box icon-total">📦</div>
                <div class="stat-info">
                    <span>TOTAL PRODUK</span>
                    <h2><?= $total_produk ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box icon-low">⚠️</div>
                <div class="stat-info">
                    <span>STOK RENDAH</span>
                    <h2><?= $stok_rendah ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div class="icon-box icon-active">❌</div>
                <div class="stat-info">
                    <span>PRODUK AKTIF</span>
                    <h2><?= $produk_habis ?></h2>
                </div>
            </div>
        </div>

        <!-- DATA CARD -->



        <div class="data-card">
            <!-- TOOLBAR -->
            <div class="toolbar">
                <p>Daftar menu</p>
               
            </div>

            <div class="grid-wrapper">
                <div class="grid-header">
                    <div>Produk</div>
                    <div>Kategori</div>
                    <div>Harga</div>
                    <div>Stok</div>
                    <div>Rating</div>
                    <div style="text-align: right;">Aksi</div>
                </div>

                <?php while ($menu = $query->fetch_assoc()): ?>
                    <?php
                    $status = ((int)$menu['STOK'] <= 0) ? 'habis' : 'tersedia';
                    ?>
                    <div class="grid-row">

                        <!-- PRODUK -->
                        <div class="product-info">
                            <div class="img-placeholder">
                                <img
                                    src="../../source/gambar_menu/<?= htmlspecialchars($menu['FOTO_MENU']) ?>"
                                    width="45"
                                    height="45"
                                    style="border-radius:10px; object-fit:cover;">
                            </div>

                            <strong><?= htmlspecialchars($menu['NAMA_MENU']) ?></strong>
                        </div>

                        <!-- KATEGORI -->
                        <div><?= htmlspecialchars($menu['KATEGORI']) ?></div>

                        <!-- HARGA -->
                        <div>Rp <?= number_format($menu['HARGA'], 0, ',', '.') ?></div>

                        <!-- STOK -->
                        <div>
                            <div>
                                <?php if ((int)$menu['STOK'] <= 0): ?>
                                    <span class="badge badge-red">
                                        Habis
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-green">
                                        <?= (int)$menu['STOK'] ?> Tersedia
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- RATING -->
                        <div>
                            ⭐ <?= number_format($menu['RATING'] ?? 0, 1) ?>/5
                        </div>

                        <!-- AKSI -->
                        <div class="actions">
                            📝 🗑️
                        </div>

                    </div>
                <?php endwhile; ?>
            </div>
            <!-- FOOTER -->

        </div>
    </div>
</body>

</html>