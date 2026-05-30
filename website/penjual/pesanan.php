<?php
// 1. Hubungkan ke file koneksi bawaan kamu
require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/penjualC.php";

// Aktifkan session jika belum aktif untuk tahu siapa yang login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id_user_login = $_SESSION['id_user'] ?? 24;

// Cari ID Kantin yang dimiliki oleh penjual ini dari tabel list_kantin
$sql_kantin = "SELECT ID FROM list_kantin WHERE id_penjual = '$id_user_login' LIMIT 1";
$query_kantin = $conn->query($sql_kantin);
$data_kantin = $query_kantin->fetch_assoc();

$id_kantin_toko = $data_kantin['ID'] ?? 1;

// --- PROSES UPDATE STATUS PESANAN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_transaksi']) && isset($_POST['status_baru'])) {
    $id_transaksi_update = $conn->real_escape_string($_POST['id_transaksi']);
    $status_baru = $conn->real_escape_string($_POST['status_baru']);
    
    // Update status transaksi di database
    $sql_update = "UPDATE transaksi SET status = '$status_baru' WHERE ID_TRANSAKSI = '$id_transaksi_update' AND id_kantin = '$id_kantin_toko'";
    if ($conn->query($sql_update)) {
        // Refresh halaman agar perubahan terlihat
        header("Location: pesanan.php?status_filter=" . ($_GET['status_filter'] ?? 'semua') . "&msg=success");
        exit;
    }
}

// --- LOGIK FILTER STATUS ---
$status_filter = $_GET['status_filter'] ?? 'semua';
$where_clause = "WHERE t.id_kantin = '$id_kantin_toko'";
if ($status_filter !== 'semua') {
    $where_clause .= " AND t.status = '" . $conn->real_escape_string($status_filter) . "'";
}

// --- QUERY TOTAL NOTIFIKASI COUNTER BAGIAN ATAS ---
$sql_counter = "
    SELECT 
        COUNT(ID_TRANSAKSI) as total_semua,
        SUM(CASE WHEN status = 'baru' OR status = 'pending' THEN 1 ELSE 0 END) as total_baru,
        SUM(CASE WHEN status = 'diproses' THEN 1 ELSE 0 END) as total_diproses,
        SUM(CASE WHEN status = 'dikonfirmasi' THEN 1 ELSE 0 END) as total_konfirmasi,
        SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as total_selesai,
        SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END) as total_batal
    FROM transaksi 
    WHERE id_kantin = '$id_kantin_toko'
";
$query_counter = $conn->query($sql_counter);
$counts = $query_counter->fetch_assoc();

// --- QUERY DAFTAR PESANAN (Urutan Paling Baru di Atas Berdasarkan ID/Waktu) ---
$sql_transaksi = "
    SELECT 
        t.ID_TRANSAKSI AS id_transaksi, 
        t.kode_pesanan, 
        t.waktu, 
        t.tgl,
        t.total, 
        t.status, 
        t.catatan,
        u.NAMA_LENGKAP AS nama_pembeli,
        u.PASS AS password_pembeli, /* Digunakan untuk mockup pw 12345 jika diperlukan */
        (
            SELECT GROUP_CONCAT(CONCAT(dt.nama_menu, ' (', dt.qty, ')') SEPARATOR '<br>') 
            FROM detail_transaksi dt 
            WHERE dt.id_transaksi = t.ID_TRANSAKSI
        ) AS detail_menu
    FROM transaksi t
    LEFT JOIN users u ON t.id_user = u.ID
    $where_clause
    ORDER BY t.ID_TRANSAKSI DESC, t.tgl DESC, t.waktu DESC
";

$query_transaksi = $conn->query($sql_transaksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan Masuk</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- RESET & GLOBAL STYLE --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #334155;
            padding: 20px 0;
        }
        ::-webkit-scrollbar { width: 0px; background: transparent; }
        * { scrollbar-width: none; -ms-overflow-style: none; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px;
        }

        /* --- HEADER --- */
        .header-title h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }
        .header-title p {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
            margin-bottom: 24px;
        }

        /* --- TABS CHIPS (FILTER STATUS) --- */
        .tabs-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        .tab-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 25px;
            text-decoration: none;
            color: #475569;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.2s;
        }
        .tab-chip.active {
            background: #ff6600;
            color: #ffffff;
            border-color: #ff6600;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #3b82f6;
            color: white;
            font-size: 11px;
            font-weight: 600;
            width: 18px;
            height: 18px;
            border-radius: 50%;
        }
        .tab-chip.active .badge {
            background: #ffffff;
            color: #ff6600;
        }
        .badge.badge-orange { background: #ff6600; }
        .badge.badge-purple { background: #8b5cf6; }

        /* --- INDIKATOR WARNA STATUS (MATCH MOCKUP) --- */
        .border-baru, .border-pending { border-left: 5px solid #ffcc00 !important; }
        .border-dikonfirmasi { border-left: 5px solid #3b82f6 !important; }
        .border-diproses { border-left: 5px solid #8b5cf6 !important; }
        .border-selesai { border-left: 5px solid #10b981 !important; }
        .border-dibatalkan { border-left: 5px solid #ef4444 !important; }

        .status-pill {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }
        .pill-baru, .pill-pending { background: #fffbeb; color: #b45309; }
        .pill-dikonfirmasi { background: #eff6ff; color: #1d4ed8; }
        .pill-diproses { background: #f5f3ff; color: #6d28d9; }
        .pill-selesai { background: #ecfdf5; color: #047857; }
        .pill-dibatalkan { background: #fef2f2; color: #b91c1c; }

        /* --- NOTIFIKASI ALERTS --- */
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 14px;
        }

        /* ========================================================
           DESKTOP VIEW IMPLEMENTATION (TABLE LAYOUT)
           ======================================================== */
        .desktop-card-panel {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .panel-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 20px;
        }
        .grid-table {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .grid-row-header {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1.8fr 1fr 1.5fr 0.8fr;
            padding: 12px 16px;
            gap: 12px;
            background-color: #f1f5f9;
            border-radius: 8px;
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .grid-row-data {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1.8fr 1fr 1.5fr 0.8fr;
            align-items: center;
            padding: 16px;
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            background: #fff;
            margin-bottom: 4px;
            border-radius: 4px;
        }
        .grid-row-data:hover {
            background-color: #f8fafc;
        }

        /* Dropdown custom styling matching Gambar 3 */
        .select-status {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            outline: none;
            background: #fff;
            color: #334155;
            width: 100%;
        }
        .btn-detail {
            display: inline-block;
            padding: 8px 14px;
            background-color: #fff5eb;
            color: #e06313;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-align: center;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .btn-detail:hover {
            background-color: #e06313;
            color: #ffffff;
        }

        /* --- MOBILE LAYOUT SYSTEM (HIDDEN BY DEFAULT ON DESKTOP) --- */
        .mobile-orders-container {
            display: none;
        }

        /* ========================================================
           RESPONSIVE BREAKPOINT: MOBILE CONVERSION
           ======================================================== */
        @media (max-width: 768px) {
            .desktop-card-panel {
                display: none; /* Sembunyikan panel desktop total */
            }
            .mobile-orders-container {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            
            /* Card design matching Gambar 1 */
            .mobile-order-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 16px;
                display: flex;
                flex-direction: column;
                gap: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            }
            .card-row-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }
            .card-meta-left {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }
            .txt-kode {
                font-size: 12px;
                color: #94a3b8;
                font-weight: 500;
            }
            .txt-waktu {
                font-size: 12px;
                color: #64748b;
            }
            .txt-pembeli {
                font-size: 16px;
                font-weight: 700;
                color: #1e293b;
                margin-top: 4px;
            }
            .txt-menu {
                font-size: 14px;
                color: #475569;
                line-height: 1.5;
                margin-top: 4px;
            }
            .card-row-bottom {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 6px;
                padding-top: 10px;
                border-top: 1px dashed #f1f5f9;
            }
            .price-wrapper {
                display: flex;
                flex-direction: column;
            }
            .lbl-total {
                font-size: 11px;
                color: #94a3b8;
                text-transform: uppercase;
                font-weight: 500;
            }
            .txt-total {
                font-size: 16px;
                font-weight: 700;
                color: #ff6600;
            }
            .actions-wrapper-mobile {
                display: flex;
                align-items: center;
                gap: 8px;
                width: 60%;
            }
            .mobile-order-card .select-status {
                padding: 6px 8px;
                font-size: 12px;
            }
            .mobile-order-card .btn-detail {
                padding: 7px 12px;
                font-size: 12px;
                width: auto;
            }
        }

        /* --- MODAL NOTA POPUP --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex; justify-content: center; align-items: center;
            opacity: 0; pointer-events: none;
            transition: all 0.3s ease; z-index: 9999;
        }
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-content {
            background: #ffffff; border-radius: 16px; padding: 24px;
            width: 90%; max-width: 500px; position: relative;
            transform: translateY(-20px); transition: all 0.3s ease;
            max-height: 90vh; overflow-y: auto;
        }
        .modal-overlay.active .modal-content { transform: translateY(0); }
        .close-modal {
            position: absolute; top: 16px; right: 20px;
            font-size: 28px; color: #94a3b8; cursor: pointer;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt="Logo"></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span><span></span><span></span>
            </label>
            <ul class="nav-links">
                <li><a href="penjual.php">Beranda</a></li>
                   <li><a href="pendapatan.php" >Pendapatan</a></li>
                <li><a href="pesanan.php" class="active">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php" >Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 70px;">
        <header class="header-title">
            
        </header>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div class="alert-success">
                Status pesanan berhasil diperbarui!
            </div>
        <?php endif; ?>

        <div class="tabs-container">
            <a href="pesanan.php?status_filter=semua" class="tab-chip <?php echo $status_filter == 'semua' ? 'active' : ''; ?>">
                Semua Pesanan
            </a>
            <a href="pesanan.php?status_filter=pending" class="tab-chip <?php echo $status_filter == 'pending' ? 'active' : ''; ?>">
                Baru <span class="badge badge-orange"><?php echo $counts['total_baru'] ?? 0; ?></span>
            </a>
           
            <a href="pesanan.php?status_filter=dikonfirmasi" class="tab-chip <?php echo $status_filter == 'dikonfirmasi' ? 'active' : ''; ?>">
                Dikonfirmasi<span class="badge badge-orange"><?php echo $counts['total_konfirmasi'] ?? 0; ?></span>
            </a> 
             <a href="pesanan.php?status_filter=diproses" class="tab-chip <?php echo $status_filter == 'diproses' ? 'active' : ''; ?>">
                Diproses <span class="badge"><?php echo $counts['total_diproses'] ?? 0; ?></span>
            </a>
            <a href="pesanan.php?status_filter=selesai" class="tab-chip <?php echo $status_filter == 'selesai' ? 'active' : ''; ?>">
                Selesai
            </a>
            <a href="pesanan.php?status_filter=dibatalkan" class="tab-chip <?php echo $status_filter == 'dibatalkan' ? 'active' : ''; ?>">
                Dibatalkan
            </a>
        </div>

        <main class="desktop-card-panel">
            <div class="panel-title">Semua Pesanan</div>
            <div class="grid-table">
                
                <div class="grid-row-header">
                    <div>Waktu & Kode</div>
                    <div>Pembeli</div>
                    <div>Detail Pesanan</div>
                    <div>Total Harga</div>
                    <div>Ubah Status</div>
                    <div>Aksi</div>
                </div>

                <?php if ($query_transaksi && $query_transaksi->num_rows > 0): ?>
                    <?php 
                    // Reset pointer data biar bisa di-looping ulang untuk mobile di bawah
                    $transaksi_data = [];
                    while ($row = $query_transaksi->fetch_assoc()) {
                        $transaksi_data[] = $row;
                        $status_clean = strtolower($row['status']);
                        // Fallback ke border pending jika status bernilai 'baru'
                        $border_class = ($status_clean == 'baru') ? 'border-pending' : 'border-' . $status_clean;
                    ?>
                        <div class="grid-row-data <?php echo $border_class; ?>">
                            <div>
                                <span style="font-size: 12px; color: #94a3b8;"><?php echo date('d M Y, H:i', strtotime($row['tgl'] . ' ' . $row['waktu'])); ?></span><br>
                                <span style="font-weight:600; color:#e06313;"><?php echo htmlspecialchars($row['kode_pesanan'] ?: '#TRX-'.$row['id_transaksi']); ?></span>
                            </div>
                            
                            <div>
                                <strong><?php echo htmlspecialchars($row['nama_pembeli'] ?? 'Guest'); ?></strong>
                            </div>
                            
                            <div>
                                <div style="font-size: 13px; color: #475569; line-height: 1.5;">
                                    <?php echo $row['detail_menu'] ?: '-'; ?>
                                </div>
                                <?php if (!empty($row['catatan'])): ?>
                                    <div style="font-size:12px; color:#ef4444; margin-top:4px; font-style:italic;">
                                        Catatan: <?php echo htmlspecialchars($row['catatan']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <strong style="color: #0f172a; font-size: 15px;">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></strong>
                            </div>
                            
                            <div>
                                <form action="" method="POST">
                                    <input type="hidden" name="id_transaksi" value="<?php echo $row['id_transaksi']; ?>">
                                    <select name="status_baru" onchange="this.form.submit()" class="select-status">
                                        <option value="pending" <?php echo ($row['status'] == 'pending' || $row['status'] == 'baru') ? 'selected' : ''; ?>>🟢 Pending</option>
                                        <option value="dikonfirmasi" <?php echo $row['status'] == 'dikonfirmasi' ? 'selected' : ''; ?>>🔵 Dikonfirmasi</option>
                                        <option value="diproses" <?php echo $row['status'] == 'diproses' ? 'selected' : ''; ?>>🟣 Diproses</option>
                                        <option value="selesai" <?php echo $row['status'] == 'selesai' ? 'selected' : ''; ?>>🟢 Selesai</option>
                                        <option value="dibatalkan" <?php echo $row['status'] == 'dibatalkan' ? 'selected' : ''; ?>>🔴 Dibatalkan</option>
                                    </select>
                                </form>
                            </div>

                            <div>
                                <button type="button" class="btn-detail btn-buka-modal" data-id="<?php echo $row['id_transaksi']; ?>">Detail</button>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #94a3b8;">Belum ada pesanan masuk.</div>
                <?php endif; ?>
            </div>
        </main>

        <main class="mobile-orders-container">
            <?php if (!empty($transaksi_data)): ?>
                <?php foreach ($transaksi_data as $row): 
                    $status_clean = strtolower($row['status']);
                    $border_class = ($status_clean == 'baru') ? 'border-pending' : 'border-' . $status_clean;
                    $pill_class = ($status_clean == 'baru') ? 'pill-pending' : 'pill-' . $status_clean;
                ?>
                    <div class="mobile-order-card <?php echo $border_class; ?>">
                        <div class="card-row-top">
                            <div class="card-meta-left">
                                <span class="txt-kode"><?php echo htmlspecialchars($row['kode_pesanan'] ?: '#TRX-'.$row['id_transaksi']); ?></span>
                                <span class="txt-waktu"><?php echo date('d M Y, H:i', strtotime($row['tgl'] . ' ' . $row['waktu'])); ?></span>
                                <div class="txt-pembeli"><?php echo htmlspecialchars($row['nama_pembeli'] ?? 'Guest'); ?></div>
                            </div>
                            <span class="status-pill <?php echo $pill_class; ?>">
                                <?php echo ($row['status'] == 'pending' || $row['status'] == 'baru') ? 'BARU' : $row['status']; ?>
                            </span>
                        </div>

                        <div class="txt-menu">
                            <?php echo $row['detail_menu'] ?: '-'; ?>
                            <?php if (!empty($row['catatan'])): ?>
                                <div style="font-size:12px; color:#ef4444; margin-top:4px; font-style:italic;">
                                    Cat: <?php echo htmlspecialchars($row['catatan']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-row-bottom">
                            <div class="price-wrapper">
                                <span class="lbl-total">Total Harga</span>
                                <span class="txt-total">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></span>
                            </div>
                            
                            <div class="actions-wrapper-mobile">
                                <form action="" method="POST" style="flex: 1;">
                                    <input type="hidden" name="id_transaksi" value="<?php echo $row['id_transaksi']; ?>">
                                    <select name="status_baru" onchange="this.form.submit()" class="select-status">
                                        <option value="pending" <?php echo ($row['status'] == 'pending' || $row['status'] == 'baru') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="dikonfirmasi" <?php echo $row['status'] == 'dikonfirmasi' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                        <option value="diproses" <?php echo $row['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                        <option value="selesai" <?php echo $row['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                        <option value="dibatalkan" <?php echo $row['status'] == 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                                    </select>
                                </form>
                                <button type="button" class="btn-detail btn-buka-modal" data-id="<?php echo $row['id_transaksi']; ?>">Detail</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #94a3b8;">Belum ada pesanan masuk.</div>
            <?php endif; ?>
        </main>
    </div>

    <div class="modal-overlay" id="modalDetailPesanan">
        <div class="modal-content">
            <span class="close-modal" id="btnTutupModal">&times;</span>
            <div id="kontenModalNota">
                <div style="text-align:center; padding: 20px;">
                    <p style="color:#64748b;">Memuat rincian nota...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("modalDetailPesanan");
            const tutupModal = document.getElementById("btnTutupModal");
            const kontenModal = document.getElementById("kontenModalNota");
            const tombolBuka = document.querySelectorAll(".btn-buka-modal");

            tombolBuka.forEach(button => {
                button.addEventListener("click", function() {
                    const idTransaksi = this.getAttribute("data-id");
                    modal.classList.add("active");
                    kontenModal.innerHTML = '<div style="text-align:center; padding: 20px;"><p style="color:#64748b;">Memuat rincian nota...</p></div>';

                    fetch(`detail_pesanan.php?id=${idTransaksi}`)
                        .then(response => response.text())
                        .then(html => { kontenModal.innerHTML = html; })
                        .catch(error => {
                            kontenModal.innerHTML = '<div style="text-align:center; padding: 20px;"><p style="color:#ef4444;">Gagal memuat rincian nota!</p></div>';
                        });
                });
            });

            tutupModal.addEventListener("click", function() { modal.classList.remove("active"); });
            window.addEventListener("click", function(e) { if (e.target === modal) { modal.classList.remove("active"); } });
        });
    </script>
</body>
</html>