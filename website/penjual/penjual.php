<?php
// ===============================
// KONEKSI & SESSION
// ===============================
require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/penjualC.php";
// session_start();

// ===============================
// PROTEKSI HALAMAN
// ===============================
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    header("location: ../login.php");
    exit();
}

// ===============================
// USER LOGIN
// ===============================
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

// ===============================
// RATING RATA-RATA
// ===============================
$sql_rating = "SELECT AVG(rating) AS avg_rating FROM tb_menu WHERE id_kantin = '$id_kantin_toko'";
$query_rating = $conn->query($sql_rating);

if (!$query_rating) {
    die("Query rating error: " . $conn->error);
}
$data_rating = $query_rating->fetch_assoc();
$avg_rating = $data_rating['avg_rating'] ?? 0;

// ===============================
// PENDAPATAN HARI INI (FIX STATUS: Selesai)
// ===============================
$sql_pendapatan = "
SELECT SUM(TOTAL) AS total 
FROM transaksi 
WHERE id_kantin = '$id_kantin_toko' 
AND DATE(TGL) = CURDATE() 
AND STATUS = 'selesai'
";
$query_pendapatan = $conn->query($sql_pendapatan);
$data_pendapatan = $query_pendapatan->fetch_assoc();
$total_hari_ini = $data_pendapatan['total'] ?? 0;

// ===============================
// TOTAL MENU/PRODUK AKTIF
// ===============================
$sql_produk = "SELECT COUNT(*) AS total_produk FROM tb_menu WHERE id_kantin = '$id_kantin_toko' AND STATUS != 'nonaktif'";
$query_produk = $conn->query($sql_produk);
$data_produk = $query_produk->fetch_assoc();
$total_produk = $data_produk['total_produk'] ?? 0;

// ==========================================
// KONFIGURASI PAGINATION (TRANSAKSI HARI INI)
// ==========================================
$limit = 10; // Jumlah baris data transaksi per halaman
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $limit;

// 1. Kueri untuk hitung TOTAL DATA transaksi khusus HARI INI
$sql_total_trx = "
    SELECT COUNT(DISTINCT t.ID_TRANSAKSI) AS total_data 
    FROM transaksi t
    WHERE t.id_kantin = '$id_kantin_toko' 
    AND DATE(t.TGL) = CURDATE()
    AND t.STATUS = 'selesai'
";
$query_total_trx = $conn->query($sql_total_trx);
$data_total_trx = $query_total_trx->fetch_assoc();
$total_data = $data_total_trx['total_data'] ?? 0;

// Menghitung total halaman pembulatan ke atas
$total_halaman = ceil($total_data / $limit);

// =========================================================
// QUERY: RIWAYAT TRANSAKSI (Khusus Hari Ini + Pagination)
// =========================================================
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
WHERE t.id_kantin = '$id_kantin_toko'
AND DATE(t.TGL) = CURDATE()
AND t.STATUS = 'selesai'
GROUP BY t.ID_TRANSAKSI
ORDER BY t.WAKTU DESC
LIMIT $limit OFFSET $offset
";
$query_transaksi = $conn->query($sql_transaksi);

if (!$query_transaksi) {
    die("Query transaksi error: " . $conn->error);
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

        /* Invisible Scrollbar Vertikal Global */
        ::-webkit-scrollbar {
            width: 0px !important;
            background: transparent !important;
        }

        html,
        body,
        *,
        div {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            padding: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* --- SUMMARY GRID --- */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .card-summary {
            background: #ffffff;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #eaeaea;
        }

        .card-icon-trend {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-pendapatan {
            background-color: #fbeee6;
            color: #e06313;
        }

        .icon-terjual {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        .icon-pesanan {
            background-color: #fff4bd;
            color: #d9a400;
        }

        .card-summary p {
            font-size: 14px;
            color: #666;
        }

        .card-summary h1,
        .card-summary h2 {
            font-size: 26px;
            font-weight: 700;
            color: #111;
            margin-top: 4px;
        }

        /* --- MAIN LAYOUT CONTENT --- */
        .main-layout {
            display: block;
            width: 100%;
        }

        .card-section {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #eaeaea;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111;
            margin-bottom: 20px;
        }

        /* --- GRID TABLE KODE AWAL KAMU (DESKTOP) --- */
        .grid-table {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .grid-row-header {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr 1fr 1fr 0.8fr;
            /* Sesuai kode awalmu */
            align-items: center;
            padding: 12px 8px;
            border-bottom: 2px solid #f5f5f5;
            font-weight: 500;
            color: #666;
            font-size: 14px;
        }

        .grid-row-data {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr 1fr 1fr 0.8fr;
            /* Sesuai kode awalmu */
            align-items: center;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
            color: #333;
            padding: 16px 8px;
        }

        /* Elemen khusus pembungkus Mobile (Sembunyikan default di Desktop) */
        .mobile-left-wrapper,
        .mobile-right-wrapper {
            display: none;
        }

        .btn-detail {
            display: inline-block;
            padding: 6px 12px;
            background-color: #fbeee6;
            color: #e06313;
            text-decoration: none;
            max-width: 90px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            text-align: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-detail:hover {
            background-color: #e06313;
            color: #ffffff;
        }

        /* --- MODAL POP-UP --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 9999;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-20px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            font-weight: 600;
            color: #999;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #333;
        }

        /* ========================================================
           MEDIA QUERY PLATFORM MOBILE (PAS HP DIUBAH TOTAL)
           ======================================================== */
        /* ========================================================
           MEDIA QUERY PLATFORM MOBILE (PAS HP DIUBAH TOTAL)
           ======================================================== */
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            /* 3 Kotak Atas: 1 Pendapatan Full di Atas, 2 Lainnya Berjejer di Bawah */
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .card-summary:nth-child(1) {
                grid-column: span 2;
            }

            /* Hilangkan header kolom bawaan desktop */
            .grid-row-header {
                display: none;
            }

            /* Desain ulang baris data versi Mobile */
            .grid-row-data {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 14px 12px;
                background: #ffffff;
                border: 1px solid #eaeaea;
                border-radius: 12px;
                margin-bottom: 10px;
            }

            /* Sembunyikan kolom satuan asli desktop biar gak numpuk */
            .desktop-cell {
                display: none !important;
            }

            /* Tampilkan & Desain Pembungkus Kiri Mobile (Diberi !important agar aktif di mobile) */
            .grid-row-data .mobile-left-wrapper {
                display: flex !important;
                flex-direction: column;
                gap: 4px;
                width: 65%;
            }

            .mb-meta-top {
                display: flex;
                gap: 8px;
                font-size: 12px;
                color: #718096;
            }

            .mb-time {
                font-weight: 600;
                color: #e06313;
            }

            .mb-id {
                color: #4a5568;
            }

            .mb-menu-list {
                font-size: 14px;
                font-weight: 500;
                color: #1a202c;
            }

            /* Tampilkan & Desain Pembungkus Kanan Mobile (Diberi !important agar aktif di mobile) */
            .grid-row-data .mobile-right-wrapper {
                display: flex !important;
                flex-direction: column;
                align-items: flex-end;
                gap: 8px;
                width: 30%;
            }

            .mb-price {
                font-size: 14px;
                font-weight: 700;
                color: #2d3748;
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
            <div class="card-summary">
                <div class="card-icon-trend">
                    <div class="icon-box icon-pendapatan">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
                <p>Total Pendapatan</p>
                <h1>Rp <?= number_format($total_hari_ini, 0, ',', '.'); ?></h1>
            </div>

            <div class="card-summary">
                <div class="card-icon-trend">
                    <div class="icon-box icon-terjual">
                        <i class="fa-solid fa-box"></i>
                    </div>
                </div>
                <p>Total Produk</p>
                <h2><?= $total_produk; ?> Produk</h2>
            </div>

            <div class="card-summary">
                <div class="card-icon-trend">
                    <div class="icon-box icon-pesanan">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                <p>Rating Toko</p>
                <h2><?= number_format($avg_rating, 1); ?> / 5.0</h2>
            </div>
        </section>

        <main class="main-layout">
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
                    // Memecah teks berdasarkan tanda kurung buka '('
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
<?php endif; ?>  </div>
        </main>
    </div>

    <div class="modal-overlay" id="modalDetailPesanan">
        <div class="modal-content">
            <span class="close-modal" id="btnTutupModal">&times;</span>
            <div id="kontenModalNota">
                <p style="text-align:center; color:#888;">Memuat rincian...</p>
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
                    kontenModal.innerHTML = '<p style="text-align:center; color:#888;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat rincian...</p>';

                    fetch(`detail_pesanan.php?id=${idTransaksi}`)
                        .then(response => response.text())
                        .then(html => {
                            kontenModal.innerHTML = html;
                        })
                        .catch(error => {
                            kontenModal.innerHTML = '<p style="text-align:center; color:red;">Gagal memuat rincian nota!</p>';
                        });
                });
            });

            tutupModal.addEventListener("click", function() {
                modal.classList.remove("active");
            });
            window.addEventListener("click", function(e) {
                if (e.target === modal) {
                    modal.classList.remove("active");
                }
            });
        });
    </script>
</body>

</html>