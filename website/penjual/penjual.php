<?php
// ===============================
// KONEKSI & SESSION
// ===============================
require_once __DIR__ . "/../include/koneksi.php";

session_start();

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
$sql_kantin = "
SELECT ID 
FROM list_kantin 
WHERE id_penjual = '$id_user_login'
LIMIT 1
";

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
// TOTAL PRODUK TERJUAL HARI INI
// ===============================
$sql_produk = "
SELECT SUM(dt.qty) AS total_produk
FROM detail_transaksi dt
JOIN transaksi t 
ON dt.id_transaksi = t.ID_TRANSAKSI


WHERE t.id_kantin = '$id_kantin_toko'
AND DATE(t.tgl) = CURDATE()

";
$query_produk = $conn->query($sql_produk);

if (!$query_produk) {
    die("Query produk error: " . $conn->error);
}

$data_produk = $query_produk->fetch_assoc();

$total_produk_terjual = $data_produk['total_produk'] ?? 0;

// ===============================
// RATING RATA-RATA
// ===============================
$sql_rating = "
SELECT AVG(rating) AS avg_rating
FROM tb_menu
WHERE id_kantin = '$id_kantin_toko'
";

$query_rating = $conn->query($sql_rating);

if (!$query_rating) {
    die("Query rating error: " . $conn->error);
}

$data_rating = $query_rating->fetch_assoc();

$avg_rating = $data_rating['avg_rating'] ?? 0;

// ===============================
// RIWAYAT TRANSAKSI
// ===============================
$sql_transaksi = "
SELECT 
    t.ID_TRANSAKSI AS id_transaksi,
    SUM(dt.qty) AS total_qty,
    SUM(dt.subtotal) AS total_harga,
    t.WAKTU,
    t.STATUS
FROM transaksi t
JOIN detail_transaksi dt 
ON t.ID_TRANSAKSI = dt.id_transaksi

WHERE t.id_kantin = '$id_kantin_toko'
AND t.status = 'selesai'
GROUP BY t.ID_TRANSAKSI, t.waktu, t.status
ORDER BY t.waktu DESC
";

$query_transaksi = $conn->query($sql_transaksi);

if (!$query_transaksi) {
    die("Query transaksi error: " . $conn->error);
}

// ===============================
// PESANAN MASUK HARI INI
// ===============================
$sql_count = "
SELECT COUNT(*) AS total_order
FROM transaksi
WHERE ID_KANTIN = '$id_kantin_toko'
AND DATE(TGL) = CURDATE()
";

$query_count = $conn->query($sql_count);

if (!$query_count) {
    die("Query count error: " . $conn->error);
}

$data_count = $query_count->fetch_assoc();

$pesanan_masuk_hari_ini = $data_count['total_order'] ?? 0;

// ===============================
// PENDAPATAN HARI INI
// ===============================
$sql_pendapatan = "
SELECT SUM(dt.subtotal) AS total
FROM detail_transaksi dt
JOIN transaksi t 
ON dt.ID_TRANSAKSI = t.ID_TRANSAKSI
WHERE DATE(t.tgl) = CURDATE()

AND t.ID_KANTIN = '$id_kantin_toko'
";

$query_pendapatan = $conn->query($sql_pendapatan);

if (!$query_pendapatan) {
    die("Query pendapatan error: " . $conn->error);
}

$data_pendapatan = $query_pendapatan->fetch_assoc();

$total_hari_ini = $data_pendapatan['total'] ?? 0;

// ===============================
// PRODUK TERJUAL HARI INI
// ===============================
$sql_produk = "
SELECT SUM(dt.qty) AS total_produk
FROM detail_transaksi dt
JOIN transaksi t ON dt.id_transaksi = t.ID_TRANSAKSI
WHERE t.id_kantin = '$id_kantin_toko'
AND DATE(t.tgl) = CURDATE()
";

$query_produk = $conn->query($sql_produk);
$data_produk = $query_produk->fetch_assoc();

$total_produk_terjual = $data_produk['total_produk'] ?? 0;

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Kantin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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

        /* --- HEADER SECTION --- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: #111;
        }

        .header-title p {
            font-size: 14px;
            color: #666;
            margin-top: 2px;
        }

        .status-toko {
            display: flex;
            align-items: center;
            background-color: #fbeee6;
            padding: 8px 16px;
            border-radius: 12px;
            border: 1px solid #f5dcd0;
        }

        .status-toko span {
            font-size: 14px;
            font-weight: 500;
            margin-right: 12px;
            color: #4a2711;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #e06313;
        }

        input:checked+.slider:before {
            transform: translateX(24px);
        }

        /* --- CARDS SUMMARY --- */
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
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-pendapatan {
            background-color: #fbeee6;
            color: #e06313;
        }

        .icon-pesanan {
            background-color: #fff4bd;
            color: #ffd500;
        }

        .icon-terjual {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        .trend-label {
            font-size: 13px;
            font-weight: 500;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-stable {
            color: #0284c7;
        }

        .card-summary p {
            font-size: 14px;
            color: #666;
        }

        .card-summary h2 {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            margin-top: 4px;
        }

        /* --- MAIN LAYOUT CONTENT (FULL WIDTH) --- */
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

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111;
        }

        .section-link {
            font-size: 13px;
            color: #e06313;
            text-decoration: none;
            font-weight: 500;
        }

        /* --- RIWAYAT TRANSAKSI DENGAN CSS GRID --- */
        .grid-table {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .grid-row-header,
        .grid-row-data {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr 1fr 1fr 0.8fr;
            align-items: center;
            padding: 12px 8px;
        }

        .grid-row-header {
            border-bottom: 2px solid #f5f5f5;
            font-weight: 500;
            color: #666;
            font-size: 14px;
        }

        .grid-row-data {
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
            color: #333;
            padding: 16px 8px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            width: max-content;
        }

        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-proses {
            background-color: #fef3c7;
            color: #92400e;
        }

        .btn-detail {
            display: inline-block;
            padding: 6px 12px;
            background-color: #fbeee6;
            color: #e06313;
            text-decoration: none;
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

        /* Responsif untuk Layar Kecil (Mobile) */
        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .grid-row-header {
                display: none;
            }

            .grid-row-data {
                grid-template-columns: 1fr 1fr 1fr;
                gap: 8px;
                padding: 12px 8px;
            }
        }

        /* --- CSS POP-UP DASAR --- */
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
                    <div class="icon-box icon-pendapatan">💵</div>
                    <span class="trend-label trend-up"></span>
                </div>
                <p>Total Pendapatan</p>
                <h1>
                    Rp <?= number_format($total_hari_ini, 0, ',', '.'); ?>
                </h1>
            </div>
            <div class="card-summary">
                <div class="card-icon-trend">
                    <div class="icon-box icon-terjual">🛍️</div>
                    <span class="trend-label trend-stable"></span>
                </div>
                <p>Total Produk</p>
                <h2><?= $total_produk_terjual; ?></h2>
            </div>
            <div class="card-summary">
                <div class="card-icon-trend">
                    <div class="icon-box icon-pesanan">⭐</div>
                    <span class="trend-label trend-up" style="color:#4f46e5;"></span>
                </div>
                <p>Rating</p>
                <h2>
                    <?= number_format($avg_rating, 1); ?>/5
                </h2>
            </div>
        </section>

        <main class="main-layout">
            <div class="card-section">
                <div class="section-header">
                    <h3>Riwayat Transaksi Mingguan</h3>
                    <a href="#" class="section-link"> </a>
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

                                <div>#-<?php echo $row['id_transaksi']; ?></div>

                                <strong>TRANSAKSI</strong>

                                <div><?php echo $row['total_qty']; ?> Porsi</div>

                                <div>
                                    Rp <?php echo number_format($row['total_harga'] ?? 0, 0, ',', '.'); ?>
                                </div>

                                <div>
                                    <?php echo date('H:i', strtotime($row['WAKTU'])); ?> WIB
                                </div>

                                <div>
                                    <button type="button"
                                        class="btn-detail btn-buka-modal"
                                        data-id="<?php echo $row['id_transaksi']; ?>">
                                        Detail
                                    </button>
                                </div>

                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="grid-row-data" style="grid-template-columns: 1fr; text-align:center; color:#888;">
                            Belum ada transaksi.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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
                    kontenModal.innerHTML = '<p style="text-align:center; color:#888;">Memuat rincian...</p>';

                    // Mengambil konten HTML dan Tag <style> langsung dari detail_pesanan.php
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