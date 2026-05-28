HP
<?php
// ========================================================
// 1. SESSION, KONEKSI, & OTENTIKASI
// ========================================================
require_once __DIR__ . '/../include/koneksi.php';
require_once __DIR__ . '/../include/session/penjualC.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    header("location: ../login.php");
    exit();
}

// Ambil ID User dari session
$id_user_login = $_SESSION['id_user'] ?? 0;

// Cari ID kantin berdasarkan id_penjual
$query_kantin = "SELECT ID FROM list_kantin WHERE id_penjual = ?";
$stmt_k = $conn->prepare($query_kantin);
$stmt_k->bind_param("i", $id_user_login);
$stmt_k->execute();
$res_k = $stmt_k->get_result();
$data_kantin = $res_k->fetch_assoc();

if (!$data_kantin) {
    echo "<div style='padding:20px; color:red; font-family:sans-serif;'>Error: Profil kantin tidak ditemukan.</div>";
    exit();
}

$id_kantin_toko = $data_kantin['ID'];

// ========================================================
// 2. QUERY KARTU STATISTIK (FIX: Tambah status 'selesai')
// ========================================================
$query_hari = "SELECT SUM(TOTAL) as total FROM transaksi WHERE id_kantin = ? AND TGL = CURDATE() AND STATUS IN ('diproses', 'dikonfirmasi', 'pending', 'selesai')";
$stmt_h = $conn->prepare($query_hari);
$stmt_h->bind_param("i", $id_kantin_toko);
$stmt_h->execute();
$pendapatan_hari = $stmt_h->get_result()->fetch_assoc()['total'] ?? 0;

$query_bulan = "SELECT SUM(TOTAL) as total FROM transaksi WHERE id_kantin = ? AND MONTH(TGL) = MONTH(CURDATE()) AND YEAR(TGL) = YEAR(CURDATE()) AND STATUS IN ('diproses', 'dikonfirmasi', 'pending', 'selesai')";
$stmt_b = $conn->prepare($query_bulan);
$stmt_b->bind_param("i", $id_kantin_toko);
$stmt_b->execute();
$pendapatan_bulan = $stmt_b->get_result()->fetch_assoc()['total'] ?? 0;

$query_trx = "SELECT COUNT(ID_TRANSAKSI) as total_trx FROM transaksi WHERE id_kantin = ? AND MONTH(TGL) = MONTH(CURDATE()) AND YEAR(TGL) = YEAR(CURDATE()) AND STATUS IN ('diproses', 'dikonfirmasi', 'pending', 'selesai')";
$stmt_t = $conn->prepare($query_trx);
$stmt_t->bind_param("i", $id_kantin_toko);
$stmt_t->execute();
$total_transaksi = $stmt_t->get_result()->fetch_assoc()['total_trx'] ?? 0;

// ========================================================
// 3. QUERY 4 PRODUK TERLARIS (Tambah status 'selesai')
// ========================================================
$query_laris = "SELECT d.NAMA_MENU, SUM(d.QTY) as total_terjual, SUM(d.SUBTOTAL) as total_duit, m.FOTO_MENU
                FROM detail_transaksi d
                LEFT JOIN tb_menu m ON d.ID_MENU = m.ID_MENU
                JOIN transaksi t ON d.ID_TRANSAKSI = t.ID_TRANSAKSI
                WHERE t.id_kantin = ? AND t.STATUS IN ('diproses', 'dikonfirmasi', 'pending', 'selesai')
                GROUP BY d.NAMA_MENU, m.FOTO_MENU 
                ORDER BY total_terjual DESC LIMIT 4";
$stmt_l = $conn->prepare($query_laris);
$stmt_l->bind_param("i", $id_kantin_toko);
$stmt_l->execute();
$produk_terlaris = $stmt_l->get_result();

// ========================================================
// 4. PAGINATION & TRANSAKSI TERAKHIR
// ========================================================
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$query_total_transaksi = "SELECT COUNT(*) as total FROM transaksi WHERE id_kantin = ?";
$stmt_total = $conn->prepare($query_total_transaksi);
$stmt_total->bind_param("i", $id_kantin_toko);
$stmt_total->execute();
$total_rows = $stmt_total->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$query_history = "SELECT t.ID_TRANSAKSI, t.KODE_PESANAN, t.TGL, t.WAKTU, t.TOTAL, t.STATUS,
                  GROUP_CONCAT(CONCAT(d.NAMA_MENU, ' (', d.QTY, ')') SEPARATOR ', ') as daftar_item
                  FROM transaksi t
                  LEFT JOIN detail_transaksi d ON t.ID_TRANSAKSI = d.ID_TRANSAKSI
                  WHERE t.id_kantin = ?
                  GROUP BY t.ID_TRANSAKSI ORDER BY t.TGL DESC, t.WAKTU DESC LIMIT ?, ?";
$stmt_hist = $conn->prepare($query_history);
$stmt_hist->bind_param("iii", $id_kantin_toko, $start, $limit);
$stmt_hist->execute();
$riwayat_transaksi = $stmt_hist->get_result();

// ========================================================
// 5. DATA UNTUK GRAFIK BATANG (Tambah status 'selesai')
// ========================================================
$grafik_minggu = array_fill(0, 5, 0);
$q_g_minggu = "SELECT WEEKDAY(TGL) as hari, SUM(TOTAL) as total FROM transaksi WHERE id_kantin = ? AND YEARWEEK(TGL, 1) = YEARWEEK(CURDATE(), 1) AND WEEKDAY(TGL) BETWEEN 0 AND 4 AND STATUS IN ('diproses', 'dikonfirmasi', 'pending', 'selesai') GROUP BY WEEKDAY(TGL)";
$stmt_g1 = $conn->prepare($q_g_minggu);
$stmt_g1->bind_param("i", $id_kantin_toko);
$stmt_g1->execute();
$res_g1 = $stmt_g1->get_result();
while ($row = $res_g1->fetch_assoc()) {
    $grafik_minggu[$row['hari']] = (int)$row['total'];
}

$grafik_bulan = array_fill(0, 12, 0);
$q_g_bulan = "SELECT MONTH(TGL) as bulan, SUM(TOTAL) as total FROM transaksi WHERE id_kantin = ? AND YEAR(TGL) = YEAR(CURDATE()) AND STATUS IN ('diproses', 'dikonfirmasi', 'pending', 'selesai') GROUP BY MONTH(TGL)";
$stmt_g2 = $conn->prepare($q_g_bulan);
$stmt_g2->bind_param("i", $id_kantin_toko);
$stmt_g2->execute();
$res_g2 = $stmt_g2->get_result();
while ($row = $res_g2->fetch_assoc()) {
    $grafik_bulan[$row['bulan'] - 1] = (int)$row['total'];
}

$tahun_sekarang = (int)date('Y');
$grafik_tahun = [];
$labels_tahun = [];
for ($i = 6; $i >= 0; $i--) {
    $t = $tahun_sekarang - $i;
    $labels_tahun[] = $t;
    $grafik_tahun[$t] = 0;
}
$q_g_tahun = "SELECT YEAR(TGL) as tahun, SUM(TOTAL) as total FROM transaksi WHERE id_kantin = ? AND YEAR(TGL) >= (? - 6) AND STATUS IN ('diproses', 'dikonfirmasi', 'pending', 'selesai') GROUP BY YEAR(TGL)";
$stmt_g3 = $conn->prepare($q_g_tahun);
$stmt_g3->bind_param("ii", $id_kantin_toko, $tahun_sekarang);
$stmt_g3->execute();
$res_g3 = $stmt_g3->get_result();
while ($row = $res_g3->fetch_assoc()) {
    if (isset($grafik_tahun[$row['tahun']])) {
        $grafik_tahun[$row['tahun']] = (int)$row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pendapatan Kantin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #F47B20;
            --primary-light: rgba(244, 123, 32, 0.08);
            --primary-hover: #e06912;
            --bg-body: #f4f6f9;
            --text-main: #2d3748;
            --text-muted: #718096;
            --border-color: #e2e8f0;
        }

        /* 1. Sembunyikan untuk browser berbasis Webkit (Chrome, Safari, Edge Baru, Opera) */
        ::-webkit-scrollbar {
            width: 0px !important;
            background: transparent !important;
        }

        /* 2. Sembunyikan untuk Firefox */
        html, body, *, div {
            scrollbar-width: none !important;
        }

        /* 3. Sembunyikan untuk Internet Explorer & Edge Lama */
        html, body, *, div {
            -ms-overflow-style: none !important;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 24px;
            box-sizing: border-box;
        }

        .dashboard-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ===== HEADER ===== */
        .dashboard-header h1 {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 4px 0;
            color: #1a202c;
        }

        .dashboard-header p {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0;
        }

        /* ===== STATS CARDS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-areas: "hari bulan total";
            gap: 20px;
        }

        .card-stat {
            background: #ffffff;
            border-radius: 16px;
            padding: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .card-hari { grid-area: hari; }
        .card-bulan { grid-area: bulan; }
        .card-total { grid-area: total; }

        .stat-info .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-info .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
            margin: 6px 0;
        }

        .stat-info .stat-subtext {
            font-size: 12px;
            color: var(--primary);
            font-weight: 500;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .icon-orange { background: var(--primary-light); color: var(--primary); }
        .icon-blue { background: #e0f2fe; color: #0284c7; }
        .icon-green { background: #dcfce7; color: #16a34a; }

        /* ===== MIDDLE ROW ===== */
        .main-content-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card-main {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border-color);
            box-sizing: border-box;
            max-width: 100%;
            overflow: hidden;
        }

        .card-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .card-title-row h3 {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: #1a202c;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 290px;
            max-height: 290px;
            box-sizing: border-box;
        }

        /* Products List */
        .top-products-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .product-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .product-meta-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-img {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            background: #edf2f7;
        }

        .product-detail-text .p-name {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin: 0 0 2px 0;
        }

        .product-detail-text .p-sold {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
            font-weight: 500;
        }

        .product-price-right {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            background: var(--primary-light);
            padding: 4px 10px;
            border-radius: 8px;
        }

        .select-filter-dropdown {
            padding: 8px 14px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: #4a5568;
            outline: none;
            cursor: pointer;
            background: #ffffff;
            transition: 0.2s;
        }

        .select-filter-dropdown:hover {
            border-color: var(--primary);
        }

        /* ===== TABLE ===== */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
            background: #ffffff;
        }

        th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #edf2f7;
            color: #4a5568;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        /* Pembungkus Baris Alternatif Mobile (Sembunyikan Default di Desktop) */
        .mobile-row-card {
            display: none;
        }

        .btn-modal-trigger {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 2px 4px rgba(244, 123, 32, 0.2);
        }

        .btn-modal-trigger:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* ===== PAGINATION ===== */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .pagination-nav {
            display: flex;
            gap: 6px;
        }

        .pagination-nav a {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            color: #4a5568;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            background: #fff;
            transition: 0.2s;
        }

        .pagination-nav a.active {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 2px 4px rgba(244, 123, 32, 0.2);
        }

        .pagination-nav a:hover:not(.active) {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ===== POPUP MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(26, 32, 44, 0.4);
            backdrop-filter: blur(5px);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; z-index: 9999;
            transition: all 0.25s ease-in-out;
        }

        .modal-overlay.open { opacity: 1; visibility: visible; }

        .modal-box {
            background: #ffffff; border-radius: 20px; width: 560px; max-width: 92%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            transform: scale(0.95); transition: all 0.25s ease-in-out;
            overflow: hidden; border: 1px solid var(--border-color);
        }

        .modal-overlay.open .modal-box { transform: scale(1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #edf2f7; background: #f8fafc; }
        .modal-header h4 { margin: 0; font-size: 16px; font-weight: 700; color: #1a202c; }
        .modal-close-btn { background: none; border: none; font-size: 20px; color: #a0aec0; cursor: pointer; transition: 0.2s; }
        .modal-close-btn:hover { color: #4a5568; }
        .modal-body { padding: 24px; max-height: 65vh; overflow-y: auto; }

        /* ========================================================
           RESPONSIVE MEDIA QUERIES (PAS DI HP)
           ======================================================== */
        @media (max-width: 1024px) {
            .main-content-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 850px) {
            body {
                padding: 14px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-areas:
                    "hari hari"
                    "bulan total";
                gap: 12px;
            }

            .card-stat { padding: 16px; }
            .stat-info .stat-value { font-size: 18px; }
            .stat-icon { width: 44px; height: 44px; font-size: 18px; }
            .chart-wrapper { height: 210px; }
            .dashboard-header h1 { font-size: 22px; }

            /* --- KONFIGURASI KHUSUS TABEL RESPONSIF DI HP --- */
            /* 1. Sembunyikan elemen table asli desktop agar tidak berantakan */
            .table-responsive table {
                display: none;
            }
            .table-responsive {
                border: none;
                background: transparent;
            }

            /* 2. Aktifkan & Desain Baris Model Card untuk HP */
            .mobile-row-card {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 16px;
                background: #ffffff;
                border: 1px solid var(--border-color);
                border-radius: 14px;
                margin-bottom: 12px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
            }

            /* Desain Sisi Kiri Mobile */
            .mb-left-content {
                display: flex;
                flex-direction: column;
                gap: 4px;
                width: 65%;
            }
            .mb-meta-top {
                display: flex;
                gap: 8px;
                font-size: 11px;
                font-weight: 500;
            }
            .mb-time { color: var(--primary); font-weight: 600; }
            .mb-id { color: var(--text-muted); }
            .mb-items-list {
                font-size: 13px;
                font-weight: 500;
                color: var(--text-main);
                line-height: 1.4;
            }

            /* Desain Sisi Kanan Mobile */
            .mb-right-content {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 8px;
                width: 32%;
            }
            .mb-total-price {
                font-size: 13px;
                font-weight: 700;
                color: var(--text-main);
            }
            .mb-right-content .btn-modal-trigger {
                width: 100%;
                max-width: 90px;
                justify-content: center;
                padding: 6px 10px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>

            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <ul class="nav-links">
                <li><a href="penjual.php">Beranda</a></li>
                <li><a href="pendapatan.php" class="active">Pendapatan</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">

        <div class="dashboard-header" style="margin-top: 60px;"></div>

        <div class="stats-grid">
            <div class="card-stat card-hari">
                <div class="stat-info">
                    <span class="stat-label">Pendapatan Hari Ini</span>
                    <div class="stat-value">Rp <?= number_format($pendapatan_hari, 0, ',', '.') ?></div>
                    <span class="stat-subtext">Hari ini</span>
                </div>
                <div class="stat-icon icon-orange"><i class="fa-solid fa-wallet"></i></div>
            </div>

            <div class="card-stat card-bulan">
                <div class="stat-info">
                    <span class="stat-label">Pendapatan Bulan Ini</span>
                    <div class="stat-value">Rp <?= number_format($pendapatan_bulan, 0, ',', '.') ?></div>
                    <span class="stat-subtext">Bulan ini</span>
                </div>
                <div class="stat-icon icon-blue"><i class="fa-solid fa-calendar-days"></i></div>
            </div>

            <div class="card-stat card-total">
                <div class="stat-info">
                    <span class="stat-label">Total Transaksi</span>
                    <div class="stat-value"><?= number_format($total_transaksi, 0, ',', '.') ?> Pesanan</div>
                    <span class="stat-subtext">Transaksi sukses</span>
                </div>
                <div class="stat-icon icon-green"><i class="fa-solid fa-bag-shopping"></i></div>
            </div>
        </div>

        <div class="main-content-row">
            <div class="card-main">
                <div class="card-title-row">
                    <h3>Tren Pendapatan</h3>
                    <select id="filterTrend" class="select-filter-dropdown" onchange="gantiSumbuGrafik(this.value)">
                        <option value="minggu">Minggu Ini</option>
                        <option value="bulan">Bulan Ini</option>
                        <option value="tahun">Tahun Ini</option>
                    </select>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chartPendapatan"></canvas>
                </div>
            </div>

            <div class="card-main">
                <div class="card-title-row">
                    <h3>Produk Terlaris</h3>
                </div>
                <div class="top-products-list">
                    <?php
                    if ($produk_terlaris->num_rows > 0) {
                        while ($p = $produk_terlaris->fetch_assoc()) {
                            $path_foto = !empty($p['FOTO_MENU']) ? "../../source/gambar_menu/" . $p['FOTO_MENU'] : "../../source/gambar_menu/default.jpg";
                    ?>
                            <div class="product-item">
                                <div class="product-meta-left">
                                    <img src="<?= htmlspecialchars($path_foto) ?>" alt="<?= htmlspecialchars($p['NAMA_MENU']) ?>" class="product-img">
                                    <div class="product-detail-text">
                                        <p class="p-name"><?= htmlspecialchars($p['NAMA_MENU']) ?></p>
                                        <p class="p-sold"><?= number_format($p['total_terjual']) ?> Porsi Terjual</p>
                                    </div>
                                </div>
                                <div class="product-price-right">Rp <?= number_format($p['total_duit'], 0, ',', '.') ?></div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p style='font-size:13px; color:#718096; text-align:center; padding:20px 0;'>Belum ada data penjualan.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="card-main">
            <div class="card-title-row">
                <h3>Transaksi Terakhir</h3>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal & Waktu</th>
                            <th>ID Transaksi</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($riwayat_transaksi->num_rows > 0) {
                            // Reset pointer data agar bisa dilooping dua kali (untuk desktop & mobile)
                            $riwayat_transaksi->data_seek(0);
                            while ($t = $riwayat_transaksi->fetch_assoc()) {
                                $tanggal_jam = date('d M Y', strtotime($t['TGL'])) . ', <span style="font-size:12px; color:#718096;">' . $t['WAKTU'] . ' WIB</span>';
                        ?>
                                <tr>
                                    <td style="font-weight: 500;"><?= $tanggal_jam ?></td>
                                    <td style="font-weight: 700; color: #1a202c;">#<?= $t['KODE_PESANAN'] ?></td>
                                    <td style="max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #4a5568;" title="<?= htmlspecialchars($t['daftar_item']) ?>">
                                        <?= htmlspecialchars($t['daftar_item']) ?>
                                    </td>
                                    <td style="font-weight: 700; color: var(--primary);">Rp <?= number_format($t['TOTAL'], 0, ',', '.') ?></td>
                                    <td style="text-align: center;">
                                        <button class="btn-modal-trigger" onclick="bukaDetailPesanan(<?= $t['ID_TRANSAKSI'] ?>, '<?= $t['KODE_PESANAN'] ?>')">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:#718096;'>Belum ada riwayat transaksi.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <?php
                if ($riwayat_transaksi->num_rows > 0) {
                    $riwayat_transaksi->data_seek(0); // Kembalikan pointer ke awal
                    while ($t = $riwayat_transaksi->fetch_assoc()) {
                        $format_tgl_mb = date('d M Y', strtotime($t['TGL']));
                ?>
                        <div class="mobile-row-card">
                            <div class="mb-left-content">
                                <div class="mb-meta-top">
                                    <span class="mb-time"><?= $t['WAKTU'] ?> WIB</span>
                                    <span class="mb-id">#<?= $t['KODE_PESANAN'] ?></span>
                                </div>
                                <div class="mb-items-list">
                                    <?= htmlspecialchars($t['daftar_item']) ?>
                                </div>
                            </div>
                            <div class="mb-right-content">
                                <div class="mb-total-price">
                                    Rp <?= number_format($t['TOTAL'], 0, ',', '.') ?>
                                </div>
                                <button class="btn-modal-trigger" onclick="bukaDetailPesanan(<?= $t['ID_TRANSAKSI'] ?>, '<?= $t['KODE_PESANAN'] ?>')">
                                    Detail
                                </button>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <?php if ($total_pages > 1) { ?>
                <div class="pagination-container">
                    <div class="pagination-info">Halaman <?= $page ?> dari <?= $total_pages ?></div>
                    <div class="pagination-nav">
                        <?php if ($page > 1) { ?><a href="?page=<?= $page - 1 ?>"><i class="fa-solid fa-chevron-left"></i></a><?php } ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <a href="?page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
                        <?php } ?>
                        <?php if ($page < $total_pages) { ?><a href="?page=<?= $page + 1 ?>"><i class="fa-solid fa-chevron-right"></i></a><?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div id="modalDetail" class="modal-overlay" onclick="tutupModalDetail(event)">
        <div class="modal-box">
            <div class="modal-header">
                <h4 id="modalTitle">Detail Transaksi</h4>
                <button class="modal-close-btn" onclick="tutupModalDetailForce()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" id="modalContainerBody"></div>
        </div>
    </div>
    <script>
        // ========================================================
        // MODAL DETAIL CONTROL
        // ========================================================
        const overlay = document.getElementById('modalDetail');
        const bodyContent = document.getElementById('modalContainerBody');
        const titleText = document.getElementById('modalTitle');

        function bukaDetailPesanan(idTransaksi, kodePesanan) {
            titleText.innerText = "Detail Transaksi #" + kodePesanan;
            overlay.classList.add('open');

            bodyContent.innerHTML = `
                <div class="loading-placeholder">
                    <div class="line-shimmer" style="width: 50%"></div>
                    <div class="line-shimmer" style="width: 90%"></div>
                    <div class="line-shimmer" style="width: 70%"></div>
                </div>`;

            fetch('detail_pesanan.php?id=' + idTransaksi)
                .then(response => response.text())
                .then(htmlOutput => {
                    bodyContent.innerHTML = htmlOutput;
                })
                .catch(error => {
                    bodyContent.innerHTML = `<p style="color:red; font-size:13px;">Gagal memuat rincian item.</p>`;
                });
        }

        function tutupModalDetail(e) {
            if (e.target === overlay) {
                overlay.classList.remove('open');
            }
        }

        function tutupModalDetailForce() {
            overlay.classList.remove('open');
        }

        // ========================================================
        // MODERN BAR CHART SETUP (SINKRON DATA SEPERTI REFERENSI)
        // ========================================================
        const dataGrafik = {
            minggu: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                data: [<?= implode(',', $grafik_minggu) ?>]
            },
            bulan: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                data: [<?= implode(',', $grafik_bulan) ?>]
            },
            tahun: {
                labels: [<?= "'" . implode("','", $labels_tahun) . "'" ?>],
                data: [<?= implode(',', $grafik_tahun) ?>]
            }
        };

        // Inisialisasi Chart.js Batang dengan Paksaan Deteksi Responsif
        const ctx = document.getElementById('chartPendapatan').getContext('2d');
        let chartTrend = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataGrafik.minggu.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: dataGrafik.minggu.data,
                    backgroundColor: '#F47B20',
                    hoverBackgroundColor: '#e06912',
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 'flex', // Menjadikan ukuran batang fleksibel mengecil otomatis di HP
                    maxBarThickness: 28 // Membatasi ketebalan maksimal di desktop
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // WAJIB FALSE agar tinggi & lebarnya otomatis menyusut di HP
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: {
                            dash: [5, 5]
                        },
                        grid: {
                            color: '#e2e8f0',
                            drawTicks: false
                        },
                        ticks: {
                            color: '#718096',
                            font: {
                                family: 'Poppins',
                                size: 10
                            },
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#718096',
                            font: {
                                family: 'Poppins',
                                size: 11,
                                weight: '500'
                            }
                        }
                    }
                }
            }
        });

        function gantiSumbuGrafik(tipe) {
            chartTrend.data.labels = dataGrafik[tipe].labels;
            chartTrend.data.datasets[0].data = dataGrafik[tipe].data;
            chartTrend.update();
        }
    </script>
</body>

</html>