<?php
// ===============================
// KONEKSI & SESSION
// ===============================
require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/penjualC.php";

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    header("location: ../login.php");
    exit();
}

$id_user_login = $_SESSION['id_user'] ?? 0;

// ===============================
// AMBIL DATA KANTIN PENJUAL
// ===============================
$sql_kantin = "SELECT ID FROM list_kantin WHERE id_penjual = '$id_user_login' LIMIT 1";
$query_kantin = $conn->query($sql_kantin);

if (!$query_kantin) {
    die("Query kantin error: " . $conn->error);
}

$data_kantin = $query_kantin->fetch_assoc();
$id_kantin_toko = $data_kantin['ID'] ?? 0;

if ($id_kantin_toko == 0) {
    die("Kantin tidak ditemukan!");
}

// Proses AJAX untuk update stok instan
if (isset($_POST['action']) && $_POST['action'] == 'update_stok_instan') {
    $id_menu_update = intval($_POST['id_menu']);
    $stok_tambahan = intval($_POST['stok_tambahan']);
    
    // Ambil stok sekarang dulu
    $res_current = $conn->query("SELECT STOK FROM tb_menu WHERE ID_MENU = $id_menu_update AND id_kantin = '$id_kantin_toko'");
    if ($res_current && $res_current->num_rows > 0) {
        $row_current = $res_current->fetch_assoc();
        $stok_baru = max(0, intval($row_current['STOK']) + $stok_tambahan);
        $status_baru = ($stok_baru > 0) ? 'tersedia' : 'habis';
        
        $update_query = $conn->query("UPDATE tb_menu SET STOK = $stok_baru, STATUS = '$status_baru' WHERE ID_MENU = $id_menu_update AND id_kantin = '$id_kantin_toko'");
        if ($update_query) {
            echo json_encode(['status' => 'success', 'message' => 'Stok berhasil diperbarui!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
    }
    exit();
}

// ===============================
// RATING RATA-RATA
// ===============================
$sql_rating = "SELECT AVG(rating) AS avg_rating FROM tb_menu WHERE id_kantin = '$id_kantin_toko'";
$query_rating = $conn->query($sql_rating);
$data_rating = $query_rating->fetch_assoc();
$avg_rating = $data_rating['avg_rating'] ?? 0;

// ===============================
// PENDAPATAN HARI INI
// ===============================
$sql_pendapatan = "SELECT SUM(TOTAL) AS total FROM transaksi WHERE id_kantin = '$id_kantin_toko' AND DATE(TGL) = CURDATE() AND STATUS = 'selesai'";
$query_pendapatan = $conn->query($sql_pendapatan);
$data_pendapatan = $query_pendapatan->fetch_assoc();
$total_hari_ini = $data_pendapatan['total'] ?? 0;

// ===============================
// TOTAL MENU / PROD HABIS
// ===============================
$sql_produk = "SELECT COUNT(*) AS total_produk FROM tb_menu WHERE id_kantin = '$id_kantin_toko' AND STATUS != 'nonaktif'";
$query_produk = $conn->query($sql_produk);
$data_produk = $query_produk->fetch_assoc();
$total_produk = $data_produk['total_produk'] ?? 0;

$sql_habis = "SELECT COUNT(*) AS produk_habis FROM tb_menu WHERE id_kantin = '$id_kantin_toko' AND (stok = 0 OR STATUS = 'habis') AND STATUS != 'nonaktif'";
$query_habis = $conn->query($sql_habis);
$data_habis = $query_habis->fetch_assoc();
$produk_habis = $data_habis['produk_habis'] ?? 0;

// ==========================================
// PAGINATION: RIWAYAT TRANSAKSI HARI INI
// ==========================================
$limit = 10;
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$sql_total_trx = "SELECT COUNT(DISTINCT t.ID_TRANSAKSI) AS total_data FROM transaksi t WHERE t.id_kantin = '$id_kantin_toko' AND DATE(t.TGL) = CURDATE() AND t.STATUS = 'selesai'";
$query_total_trx = $conn->query($sql_total_trx);
$data_total_trx = $query_total_trx->fetch_assoc();
$total_data = $data_total_trx['total_data'] ?? 0;
$total_halaman = ceil($total_data / $limit);

$sql_transaksi = "
SELECT 
    t.ID_TRANSAKSI AS id_transaksi,
    SUM(dt.QTY) AS total_qty,
    t.TOTAL AS total_harga,
    t.WAKTU,
    t.STATUS,
    GROUP_CONCAT(CONCAT(dt.NAMA_MENU, ' (', dt.QTY, ')') SEPARATOR ', ') AS daftar_menu
FROM transaksi t
LEFT JOIN detail_transaksi dt ON t.ID_TRANSAKSI = dt.ID_TRANSAKSI
WHERE t.id_kantin = '$id_kantin_toko' AND DATE(t.TGL) = CURDATE() AND t.STATUS = 'selesai'
GROUP BY t.ID_TRANSAKSI ORDER BY t.WAKTU DESC LIMIT $limit OFFSET $offset
";
$query_transaksi = $conn->query($sql_transaksi);

// ==========================================
// DATA: PRODUK HAMPIR HABIS (< 5 STOK)
// ==========================================
$sql_stok_rendah = "SELECT ID_MENU, NAMA_MENU, STOK, FOTO_MENU, KATEGORI FROM tb_menu WHERE id_kantin = '$id_kantin_toko' AND STOK < 5 AND STATUS != 'nonaktif' ORDER BY STOK ASC";
$query_stok_rendah = $conn->query($sql_stok_rendah);
$list_stok_rendah = [];
if ($query_stok_rendah) {
    while ($r = $query_stok_rendah->fetch_assoc()) {
        $list_stok_rendah[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Kantin - KantinKita</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar { width: 0px !important; background: transparent !important; }
        html, body, *, div { scrollbar-width: none !important; -ms-overflow-style: none !important; }

        body { background-color: #f5f5f5; color: #333; padding: 24px; }
        .container { max-width: 1200px; margin: 0 auto; }

        /* --- SUMMARY GRID --- */
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
        .card-link-style { text-decoration: none; color: inherit; display: block; transition: transform 0.2s ease; }
        .card-link-style:hover { transform: translateY(-4px); }
        .card-summary { background: #ffffff; padding: 24px; border-radius: 16px; display: flex; align-items: center; gap: 16px; border: 1px solid #eaeaea; }
        .card-info { display: flex; flex-direction: column; }
        .card-summary p { font-size: 13px; color: #888888; text-transform: uppercase; font-weight: 600; margin: 0; }
        .card-summary h1, .card-summary h2 { font-size: 28px; font-weight: 700; color: #111111; margin: 4px 0 0 0; line-height: 1; }
        .icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .icon-pendapatan { background-color: #fbeee6; color: #e06313; }
        .icon-terjual { background-color: #e0f2fe; color: #0284c7; }
        .icon-pesanan { background-color: #fff4bd; color: #d9a400; }

        /* --- MAIN LAYOUT TWO COLUMNS (DESKTOP) --- */
        .main-layout {
            display: grid;
            grid-template-columns: 1.6fr 1.1fr;
            gap: 24px;
            align-items: flex-start;
            width: 100%;
        }

        .card-section { background: #ffffff; border-radius: 16px; padding: 24px; border: 1px solid #eaeaea; }
        .section-header h3 { font-size: 18px; font-weight: 600; color: #111; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }

        /* --- SIDEBAR STOK PERINGATAN UX --- */
        .alert-stock-wrapper {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .stock-item-card {
            background: #fff5f5;
            border: 1px solid #ffe3e3;
            border-radius: 14px;
            padding: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: 0.2s;
        }

        .stock-item-card:hover {
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.04);
        }

        .stock-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stock-img-thumb {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffcccc;
            background: #f5f5f5;
        }

        .stock-detail-info {
            display: flex;
            flex-direction: column;
        }

        .stock-name {
            font-size: 14px;
            font-weight: 600;
            color: #111;
        }

        .stock-status-text {
            font-size: 12px;
            color: #b91c1c;
            font-weight: 500;
        }

        .btn-restock-trigger {
            padding: 6px 16px;
            background: #b91c1c;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-restock-trigger:hover {
            background: #991b1b;
        }

        /* --- MINI PAGINATION UX --- */
        .mini-pagination {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            margin-top: 14px;
        }

        .btn-mini-page {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            color: #4a5568;
            font-size: 12px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-mini-page:hover:not(.disabled) {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1a202c;
        }

        .btn-mini-page.active {
            background: #b91c1c;
            color: #ffffff;
            border-color: #b91c1c;
            font-weight: 600;
        }

        .btn-mini-page.disabled {
            color: #cbd5e1;
            background: #f8fafc;
            cursor: not-allowed;
            border-color: #e2e8f0;
        }

        /* --- GRID TABLE RIWAYAT --- */
        .grid-table { display: flex; flex-direction: column; width: 100%; }
        .grid-row-header { display: grid; grid-template-columns: 0.8fr 1.5fr 0.8fr 1fr 0.8fr 0.7fr; align-items: center; padding: 12px 8px; border-bottom: 2px solid #f5f5f5; font-weight: 500; color: #666; font-size: 14px; }
        .grid-row-data { display: grid; grid-template-columns: 0.8fr 1.5fr 0.8fr 1fr 0.8fr 0.7fr; align-items: center; border-bottom: 1px solid #f5f5f5; font-size: 14px; color: #333; padding: 16px 8px; }
        .mobile-left-wrapper, .mobile-right-wrapper { display: none; }
        
        .btn-detail { display: inline-block; padding: 6px 12px; background-color: #fbeee6; color: #e06313; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 8px; text-align: center; transition: all 0.2s ease; border: none; cursor: pointer; }
        .btn-detail:hover { background-color: #e06313; color: #ffffff; }

        /* --- GENERAL MODAL POPUP --- */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; opacity: 0; pointer-events: none; transition: all 0.25s ease; z-index: 9999; padding: 16px; }
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-content { background: #ffffff; border-radius: 20px; padding: 26px; width: 100%; max-width: 460px; position: relative; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12); transform: translateY(-20px); transition: all 0.25s ease; }
        .modal-overlay.active .modal-content { transform: translateY(0); }
        .close-modal { position: absolute; top: 16px; right: 20px; font-size: 24px; font-weight: 600; color: #94a3b8; cursor: pointer; transition: 0.2s; }
        .close-modal:hover { color: #334155; }

        /* --- STYLES FOR INSTANT RESTOCK POPUP --- */
        .restock-popup-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .restock-meta-box { display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 12px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .restock-img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; }
        .restock-info-txt h4 { font-size: 14px; font-weight: 600; color: #0f172a; }
        .restock-info-txt p { font-size: 12px; color: #64748b; }
        .counter-stock-flex { display: flex; align-items: center; justify-content: center; gap: 12px; margin: 24px 0; }
        .btn-counter { width: 42px; height: 42px; border-radius: 50%; border: 1px solid #cbd5e1; background: #fff; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; color: #334155; }
        .btn-counter:hover { background: #f1f5f9; border-color: #94a3b8; }
        
        /* Modifikasi Input agar bisa diketik dengan cantik */
        .input-stock-num { 
            width: 70px; 
            height: 42px;
            text-align: center; 
            font-size: 20px; 
            font-weight: 700; 
            border: 1px solid #cbd5e1; 
            border-radius: 10px;
            outline: none; 
            color: #0f172a; 
            background: #ffffff;
            transition: 0.2s;
        }
        .input-stock-num:focus {
            border-color: #b91c1c;
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
        }
        
        .btn-save-stock { width: 100%; height: 46px; background: #b91c1c; color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 12px rgba(185, 28, 28, 0.2); }
        .btn-save-stock:hover { background: #991b1b; }

        @media (max-width: 768px) {
            body { padding: 12px; }
            .summary-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .card-summary:nth-child(1) { grid-column: span 2; }

            .main-layout {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .main-layout .card-section:nth-child(2) {
                order: -1;
                width: 100%;
            }

            .main-layout .card-section:nth-child(1) {
                width: 100%;
            }

            .grid-row-header { display: none; }
            .grid-row-data { display: flex; justify-content: space-between; align-items: flex-start; padding: 14px 12px; background: #ffffff; border: 1px solid #eaeaea; border-radius: 12px; margin-bottom: 10px; }
            .desktop-cell { display: none !important; }
            .grid-row-data .mobile-left-wrapper { display: flex !important; flex-direction: column; gap: 4px; width: 65%; }
            .mb-meta-top { display: flex; gap: 8px; font-size: 12px; color: #718096; }
            .mb-time { font-weight: 600; color: #e06313; }
            .mb-id { color: #4a5568; }
            .mb-menu-list { font-size: 14px; font-weight: 500; color: #1a202c; }
            .grid-row-data .mobile-right-wrapper { display: flex !important; flex-direction: column; align-items: flex-end; gap: 8px; width: 30%; }
            .mb-price { font-size: 14px; font-weight: 700; color: #2d3748; }
        }
/* Memaksa Notifikasi Pop-up SweetAlert (Swal) muncul di paling depan luar modal */
.swal2-container {
    z-index: 999999 !important;
}
 </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span><span></span><span></span>
            </label>
            <ul class="nav-links">
                <li><a href="penjual.php" class="active">Beranda</a></li>
                <li><a href="pendapatan.php">Pendapatan</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 70px;">
        <section class="summary-grid">
            <!-- CARD 1: TOTAL PENDAPATAN -->
            <a href="pendapatan.php" class="card-link-style">
                <div class="card-summary">
                    <div class="card-icon-trend">
                        <div class="icon-box icon-pendapatan">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <p>Total Pendapatan</p>
                        <h1>Rp <?= number_format($total_hari_ini, 0, ',', '.'); ?></h1>
                    </div>
                </div>
            </a>

            <!-- CARD 2: TOTAL PRODUK -->
            <a href="edit1.php" class="card-link-style">
                <div class="card-summary">
                    <div class="card-icon-trend">
                        <div class="icon-box icon-terjual">
                            <i class="fa-solid fa-box"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <p>Total Produk</p>
                        <h2><?= $total_produk; ?></h2>
                    </div>
                </div>
            </a>

            <!-- CARD 3: PRODUK HABIS -->
            <a href="edit1.php?search=habis" class="card-link-style">
                <div class="card-summary">
                    <div class="card-icon-trend">
                        <div class="icon-box" style="background-color: #ffe5e5; color: #ff4d4d;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <p>Produk Habis</p>
                        <h2><?= $produk_habis; ?></h2>
                    </div>
                </div>
            </a>

            <!-- CARD 4: RATING TOKO -->
            <a href="edit1.php" class="card-link-style">
                <div class="card-summary">
                    <div class="card-icon-trend">
                        <div class="icon-box icon-pesanan">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <p>Rating Toko</p>
                        <h2><?= number_format($avg_rating, 1); ?></h2>
                    </div>
                </div>
            </a>
        </section>

        <main class="main-layout">
            <!-- SEKSYEN KIRI: RIWAYAT TRANSAKSI HARIAN -->
            <div class="card-section">
                <div class="section-header">
                    <h3>Riwayat Transaksi Harian</h3>
                </div>

                <div class="grid-table">
                    <div class="grid-row-header">
                        <div>ID Transaksi</div>
                        <div>Menu</div>
                        <div>Jumlah</div>
                        <div>Total Harga</div>
                        <div>Waktu</div>
                        <div>Aksi</div>
                    </div>

                    <?php if ($query_transaksi && $query_transaksi->num_rows > 0): ?>
                        <?php while ($row = $query_transaksi->fetch_assoc()): ?>
                            <div class="grid-row-data">
                                <div class="desktop-cell">#-<?php echo $row['id_transaksi']; ?></div>
                                <div class="desktop-cell" style="font-weight:500; color:#111;">
                                    <?php
                                    $nama_menu_saja = explode('(', $row['daftar_menu'])[0];
                                    echo htmlspecialchars(trim($nama_menu_saja));
                                    ?>
                                </div>
                                <div class="desktop-cell"><?php echo $row['total_qty']; ?> Porsi</div>
                                <div class="desktop-cell">
                                    Rp <?php echo number_format($row['total_harga'] ?? 0, 0, ',', '.'); ?>
                                </div>
                                <div class="desktop-cell">
                                    <?php echo date('H:i', strtotime($row['WAKTU'])); ?> WIB
                                </div>
                                <div class="desktop-cell">
                                    <button type="button" class="btn-detail btn-buka-modal" data-id="<?php echo $row['id_transaksi']; ?>">
                                        Detail
                                    </button>
                                </div>

                                <div class="mobile-left-wrapper">
                                    <div class="mb-meta-top">
                                        <span class="mb-time"><?= date('H:i', strtotime($row['WAKTU'])); ?> WIB</span>
                                        <span class="mb-id">#-<?= $row['id_transaksi']; ?></span>
                                    </div>
                                    <div class="mb-menu-list">
                                        <?= htmlspecialchars($row['daftar_menu'] ?? 'Menu'); ?>
                                    </div>
                                </div>

                                <div class="mobile-right-wrapper">
                                    <div class="mb-price">
                                        Rp <?= number_format($row['total_harga'] ?? 0, 0, ',', '.'); ?>
                                    </div>
                                    <button type="button" class="btn-detail btn-buka-modal" data-id="<?= $row['id_transaksi']; ?>" style="width: 100%;">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="text-align:center; color:#888; padding: 40px 0;">
                            Belum ada transaksi masuk untuk hari ini.
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($total_halaman > 1): ?>
                    <div class="pagination-wrapper" style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px;">
                        <?php if ($page > 1): ?>
                            <a href="?halaman=<?= $page - 1; ?>" class="btn-page" style="padding: 8px 12px; background: #fff; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: #333;">&laquo; Prev</a>
                        <?php else: ?>
                            <span class="btn-page disabled" style="padding: 8px 12px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 6px; color: #ccc; cursor: not-allowed;">&laquo; Prev</span>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="btn-page active" style="padding: 8px 14px; background: #F47B20; border: 1px solid #F47B20; border-radius: 6px; color: #fff; font-weight: bold;"><?= $i; ?></span>
                            <?php else: ?>
                                <a href="?halaman=<?= $i; ?>" class="btn-page" style="padding: 8px 14px; background: #fff; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: #333;"><?= $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_halaman): ?>
                            <a href="?halaman=<?= $page + 1; ?>" class="btn-page" style="padding: 8px 12px; background: #fff; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: #333;">Next &raquo;</a>
                        <?php else: ?>
                            <span class="btn-page disabled" style="padding: 8px 12px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 6px; color: #ccc; cursor: not-allowed;">Next &raquo;</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- SEKSYEN KANAN / ATAS (MOBILE): PERINGATAN STOK HABIS -->
            <div class="card-section">
                <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin-bottom: 0;">
                        Peringatan Stok Habis <span style="display:inline-block; width:8px; height:8px; background:#b91c1c; border-radius:50%; margin-left:4px;"></span>
                    </h3>
                </div>

                <div class="alert-stock-wrapper" id="js-stock-container"></div>
                <div class="mini-pagination" id="js-mini-pagination-box"></div>
            </div>
        </main>
    </div>

    <!-- MODAL DETAIL TRANSAKSI -->
    <div class="modal-overlay" id="modalDetailPesanan">
        <div class="modal-content">
            <span class="close-modal" id="btnTutupModal">&times;</span>
            <div id="kontenModalNota">
                <p style="text-align:center; color:#888;">Memuat rincian...</p>
            </div>
        </div>
    </div>

    <!-- MODAL INSTANT RESTOCK UX -->
    <div class="modal-overlay" id="modalInstantRestock">
        <div class="modal-content">
            <span class="close-modal" id="btnTutupRestock">&times;</span>
            <div class="restock-popup-title">
                <i class="fa-solid fa-layer-group" style="color: #b91c1c;"></i> Tambah Stok Produk
            </div>
            <div class="restock-meta-box">
                <img src="" id="res-modal-img" class="restock-img" alt="">
                <div class="restock-info-txt">
                    <h4 id="res-modal-name">Nama Produk</h4>
                    <p>Stok Saat Ini: <span id="res-modal-current" style="font-weight: 700; color:#b91c1c;">0</span></p>
                </div>
            </div>
            
            <p style="font-size: 13px; font-weight: 600; color: #4a5568; text-align: center;">Jumlah Stok yang Ingin Ditambahkan:</p>
            <div class="counter-stock-flex">
                <button type="button" class="btn-counter" onclick="adjustAddValue(-5)">-5</button>
                <button type="button" class="btn-counter" onclick="adjustAddValue(-1)">-1</button>
                
                <!-- Atribut readonly dihapus agar bisa di-input ketik manual -->
                <input type="number" id="js-input-add-value" class="input-stock-num" value="10" min="1" oninput="validateLiveInput(this)">
                
                <button type="button" class="btn-counter" onclick="adjustAddValue(1)">+1</button>
                <button type="button" class="btn-counter" onclick="adjustAddValue(5)">+5</button>
            </div>

            <button type="button" class="btn-save-stock" id="js-btn-submit-restock">Simpan Tambah Stok</button>
        </div>
    </div>

    <script>
        const rawStockData = <?= json_encode($list_stok_rendah); ?>;
        const itemsPerPage = 6;
        let currentStockPage = 1;
        let selectedProductForRestock = null;

        function renderStockAlert() {
            const container = document.getElementById('js-stock-container');
            const paginationBox = document.getElementById('js-mini-pagination-box');
            container.innerHTML = "";
            paginationBox.innerHTML = "";

            if (rawStockData.length === 0) {
                container.innerHTML = `<div style="text-align:center; color:#94a3b8; padding: 20px 0; font-size:14px;">Semua stok aman & terkendali!</div>`;
                return;
            }

            const start = (currentStockPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedItems = rawStockData.slice(start, end);

            paginatedItems.forEach(item => {
                const fotoPath = item.FOTO_MENU ? `../../source/gambar_menu/${item.FOTO_MENU}` : `../../source/gambar_menu/default.jpg`;
                const satuan = (item.KATEGORI === 'minuman') ? 'gelas' : 'porsi';
                
                const card = document.createElement('div');
                card.className = 'stock-item-card';
                card.innerHTML = `
                    <div class="stock-left">
                        <img src="${fotoPath}" class="stock-img-thumb" alt="${item.NAMA_MENU}">
                        <div class="stock-detail-info">
                            <span class="stock-name">${item.NAMA_MENU}</span>
                            <span class="stock-status-text">Sisa ${item.STOK} ${satuan}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-restock-trigger" onclick="openRestockModal(${item.ID_MENU}, '${item.NAMA_MENU.replace(/'/g, "\\'")}', ${item.STOK}, '${fotoPath}')">Restok</button>
                `;
                container.appendChild(card);
            });

            const totalPages = Math.ceil(rawStockData.length / itemsPerPage);
            if (totalPages > 1) {
                const prevBtn = document.createElement('span');
                prevBtn.className = `btn-mini-page ${currentStockPage === 1 ? 'disabled' : ''}`;
                prevBtn.innerHTML = `&lt;`;
                if(currentStockPage > 1) {
                    prevBtn.onclick = () => { currentStockPage--; renderStockAlert(); };
                }
                paginationBox.appendChild(prevBtn);

                for(let i=1; i<=totalPages; i++) {
                    const pageNum = document.createElement('span');
                    pageNum.className = `btn-mini-page ${currentStockPage === i ? 'active' : ''}`;
                    pageNum.innerText = i;
                    pageNum.onclick = () => { currentStockPage = i; renderStockAlert(); };
                    paginationBox.appendChild(pageNum);
                }

                const nextBtn = document.createElement('span');
                nextBtn.className = `btn-mini-page ${currentStockPage === totalPages ? 'disabled' : ''}`;
                nextBtn.innerHTML = `&gt;`;
                if(currentStockPage < totalPages) {
                    nextBtn.onclick = () => { currentStockPage++; renderStockAlert(); };
                }
                paginationBox.appendChild(nextBtn);
            }
        }

        function openRestockModal(id, name, currentStock, imgPath) {
            selectedProductForRestock = id;
            document.getElementById('res-modal-name').innerText = name;
            document.getElementById('res-modal-current').innerText = currentStock;
            document.getElementById('res-modal-img').src = imgPath;
            document.getElementById('js-input-add-value').value = "10";
            document.getElementById('modalInstantRestock').classList.add('active');
        }

        // Fungsi klik tombol plus minus
        function adjustAddValue(step) {
            const input = document.getElementById('js-input-add-value');
            let val = parseInt(input.value) || 0;
            val += step;
            if (val < 1) val = 1; 
            input.value = val;
        }

        // Fungsi Validasi ketik langsung (Mencegah input minus, kosong, atau desimal aneh)
        function validateLiveInput(inputElement) {
            let val = parseInt(inputElement.value);
            if (isNaN(val) || val < 1) {
                // Biarkan kosong dulu sementara user sedang mengetik ulang, tapi validasi minimal saat disubmit
                if (inputElement.value !== "") {
                    inputElement.value = 1;
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            renderStockAlert();

            const modal = document.getElementById("modalDetailPesanan");
            const tutupModal = document.getElementById("btnTutupModal");
            const kontenModal = document.getElementById("kontenModalNota");
            const tombolBuka = document.querySelectorAll(".btn-buka-modal");

            tombolBuka.forEach(button => {
                button.addEventListener("click", function() {
                    const idTransaksi = this.getAttribute("data-id");
                    modal.classList.add("active");
                    kontenModal.innerHTML = '<p style="text-align:center; color:#888;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat rincian...</p>';

                    fetch(`detail_pesanan.php?id=${idTransaksi}`)
                        .then(response => response.text())
                        .then(html => { kontenModal.innerHTML = html; })
                        .catch(error => { kontenModal.innerHTML = '<p style="text-align:center; color:red;">Gagal memuat rincian nota!</p>'; });
                });
            });

            tutupModal.addEventListener("click", function() { modal.classList.remove("active"); });

            const modalRestock = document.getElementById("modalInstantRestock");
            document.getElementById("btnTutupRestock").addEventListener("click", () => {
                modalRestock.classList.remove("active");
            });

            document.getElementById("js-btn-submit-restock").addEventListener("click", function() {
                let addValue = parseInt(document.getElementById('js-input-add-value').value);
                
                // Validasi final sebelum kirim data jika input kosong
                if (isNaN(addValue) || addValue < 1) {
                    addValue = 1;
                }

                if(!selectedProductForRestock) return;

                const formData = new FormData();
                formData.append('action', 'update_stok_instan');
                formData.append('id_menu', selectedProductForRestock);
                formData.append('stok_tambahan', addValue);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                });
            });

            window.addEventListener("click", function(e) {
                if (e.target === modal) modal.classList.remove("active");
                if (e.target === modalRestock) modalRestock.classList.remove("active");
            });
        });
    </script>
</body>
</html>