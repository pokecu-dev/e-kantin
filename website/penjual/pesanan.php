<?php
// 1. Hubungkan ke file koneksi bawaan kamu
require_once __DIR__ . "/../include/koneksi.php";

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
        header("Location: pesanan.php?msg=success");
        exit;
    }
}

// --- QUERY DAFTAR PESANAN MENGGUNAKAN JOIN ---
// Mengambil data transaksi dan nama pembeli dari tabel users
// Serta ringkasan menu yang dipesan menggunakan GROUP_CONCAT
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
        (
            SELECT GROUP_CONCAT(CONCAT(dt.nama_menu, ' (', dt.qty, ')') SEPARATOR '<br>') 
            FROM detail_transaksi dt 
            WHERE dt.id_transaksi = t.ID_TRANSAKSI
        ) AS detail_menu
    FROM transaksi t
    LEFT JOIN users u ON t.id_user = u.ID
    WHERE t.id_kantin = '$id_kantin_toko'
    ORDER BY t.tgl DESC, t.waktu DESC 
";

$query_transaksi = $conn->query($sql_transaksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan Masuk</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #334155;
            padding-top: 80px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
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
            font-weight: 700;
            color: #0f172a;
        }

        .header-title p {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }

        /* --- MAIN LAYOUT CONTENT --- */
        .card-section {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
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
            color: #0f172a;
        }

        /* --- CSS GRID TABLE --- */
        .grid-table {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .grid-row-header,
        .grid-row-data {
            display: grid;
            grid-template-columns: 1fr 1.5fr 2fr 1fr 1.5fr 1fr;
            align-items: start;
            padding: 12px 16px;
            gap: 12px;
        }

        .grid-row-header {
            background-color: #f1f5f9;
            border-radius: 8px;
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .grid-row-data {
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #334155;
            padding: 16px;
            transition: background-color 0.2s;
        }
        
        .grid-row-data:hover {
            background-color: #f8fafc;
        }

        .kode-pesanan {
            font-weight: 600;
            color: #e06313;
        }

        .detail-menu-list {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }

        /* --- STYLING DROPDOWN STATUS --- */
        .form-status {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .select-status {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            outline: none;
            background-color: #fff;
            color: #334155;
            transition: all 0.2s;
            width: 100%;
        }

        .select-status:focus {
            border-color: #e06313;
            box-shadow: 0 0 0 2px rgba(224, 99, 19, 0.2);
        }

        /* Warna border samping berdasarkan status */
        .status-pending { border-left: 4px solid #f59e0b; }
        .status-dikonfirmasi { border-left: 4px solid #3b82f6; }
        .status-diproses { border-left: 4px solid #8b5cf6; }
        .status-selesai { border-left: 4px solid #10b981; }
        .status-dibatalkan { border-left: 4px solid #ef4444; }

        .btn-detail {
            display: inline-block;
            padding: 8px 16px;
            background-color: #fff5eb;
            color: #e06313;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-align: center;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            width: 100%;
        }

        .btn-detail:hover {
            background-color: #e06313;
            color: #ffffff;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 14px;
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 992px) {
            .grid-row-header {
                display: none;
            }

            .grid-row-data {
                grid-template-columns: 1fr;
                gap: 12px;
                background-color: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                margin-bottom: 16px;
                padding: 16px;
            }

            .grid-row-data > div {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
            }

            .grid-row-data > div::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                font-size: 12px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }
            
            .detail-menu-list {
                text-align: left;
            }
            
            .form-status {
                width: 100%;
                margin-top: 8px;
            }
            
            .btn-detail {
                margin-top: 8px;
            }
        }
        
        /* --- MODAL POPUP STYLE --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
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
            padding: 24px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-20px);
            transition: all 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .close-modal {
            position: absolute;
            top: 16px;
            right: 20px;
            font-size: 28px;
            font-weight: 400;
            color: #94a3b8;
            cursor: pointer;
            line-height: 1;
        }

        .close-modal:hover {
            color: #0f172a;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt="Logo"></div>

            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <ul class="nav-links">
                <li><a href="penjual.php">Beranda</a></li>
                <li><a href="tespesanan.php" class="active">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container">
        <header class="header">
            <div class="header-title">
                <h1>Daftar Pesanan Masuk</h1>
                <p>Kelola dan perbarui status pesanan pelanggan.</p>
            </div>
        </header>

        <!-- Pesan Sukses -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div class="alert-success">
                Status pesanan berhasil diperbarui!
            </div>
        <?php endif; ?>

        <main class="card-section">
            <div class="section-header">
                <h3>Semua Pesanan</h3>
            </div>

            <div class="grid-table">
                <!-- Header Tabel Desktop -->
                <div class="grid-row-header">
                    <div>Waktu & Kode</div>
                    <div>Pembeli</div>
                    <div>Detail Pesanan</div>
                    <div>Total Harga</div>
                    <div>Ubah Status</div>
                    <div>Aksi</div>
                </div>

                <!-- Loop Data Transaksi -->
                <?php if ($query_transaksi && $query_transaksi->num_rows > 0): ?>
                    <?php while ($row = $query_transaksi->fetch_assoc()): 
                        // Tambahkan kelas CSS berdasarkan status untuk warna border
                        $status_class = 'status-' . strtolower($row['status']);
                    ?>
                        <div class="grid-row-data <?php echo $status_class; ?>">
                            
                            <div data-label="Waktu & Kode">
                                <span style="font-size: 12px; color: #94a3b8;"><?php echo date('d M Y, H:i', strtotime($row['tgl'] . ' ' . $row['waktu'])); ?></span><br>
                                <span class="kode-pesanan"><?php echo htmlspecialchars($row['kode_pesanan'] ?: '#TRX-'.$row['id_transaksi']); ?></span>
                            </div>
                            
                            <div data-label="Pembeli">
                                <strong><?php echo htmlspecialchars($row['nama_pembeli'] ?? 'Guest / Anonim'); ?></strong>
                            </div>
                            
                            <div data-label="Detail Pesanan">
                                <div class="detail-menu-list">
                                    <?php echo $row['detail_menu'] ? $row['detail_menu'] : '-'; ?>
                                </div>
                                <?php if (!empty($row['catatan'])): ?>
                                    <div style="font-size:12px; color:#ef4444; margin-top:6px; font-style:italic;">
                                        Catatan: <?php echo htmlspecialchars($row['catatan']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div data-label="Total Harga">
                                <strong style="color: #0f172a; font-size: 15px;">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></strong>
                            </div>
                            
                            <div data-label="Ubah Status">
                                <form action="" method="POST" class="form-status">
                                    <input type="hidden" name="id_transaksi" value="<?php echo $row['id_transaksi']; ?>">
                                    <select name="status_baru" onchange="this.form.submit()" class="select-status">
                                        <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>🟡 Pending</option>
                                        <option value="dikonfirmasi" <?php echo $row['status'] == 'dikonfirmasi' ? 'selected' : ''; ?>>🔵 Dikonfirmasi</option>
                                        <option value="diproses" <?php echo $row['status'] == 'diproses' ? 'selected' : ''; ?>>🟣 Diproses</option>
                                        <option value="selesai" <?php echo $row['status'] == 'selesai' ? 'selected' : ''; ?>>🟢 Selesai</option>
                                        <option value="dibatalkan" <?php echo $row['status'] == 'dibatalkan' ? 'selected' : ''; ?>>🔴 Dibatalkan</option>
                                    </select>
                                </form>
                            </div>

                            <div data-label="Aksi">
                                <button type="button" class="btn-detail btn-buka-modal" data-id="<?php echo $row['id_transaksi']; ?>">
                                    Detail
                                </button>
                            </div>

                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #94a3b8; width: 100%; grid-column: span 6;">
                        Belum ada pesanan yang masuk ke kantin Anda.
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- MODAL POPUP NOTA -->
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

                    // Fetch ke file detail_pesanan.php menggunakan ID
                    fetch(`detail_pesanan.php?id=${idTransaksi}`)
                        .then(response => response.text())
                        .then(html => {
                            kontenModal.innerHTML = html;
                        })
                        .catch(error => {
                            kontenModal.innerHTML = '<div style="text-align:center; padding: 20px;"><p style="color:#ef4444;">Gagal memuat rincian nota!</p></div>';
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