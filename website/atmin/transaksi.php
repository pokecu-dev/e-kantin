<?php
// session_start();
require_once __DIR__ . "/../include/session/adminC.php";
require_once __DIR__ . "/../include/koneksi.php";

// ==========================================
// PENGAMANAN EKSTRA UNTUK MENANGKAP ID KANTIN
// ==========================================
$id_kantin = 0;

// Cara 1: Ambil dari $_GET biasa (Huruf Kecil)
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id_kantin = intval($_GET['id']);
}
// Cara 2: Ambil dari $_GET biasa (Huruf Besar)
elseif (isset($_GET['ID']) && $_GET['ID'] !== '') {
    $id_kantin = intval($_GET['ID']);
}
// Cara 3: Alternatif darurat jika URL dibaca sebagai REQUEST_URI
else {
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '', $output_vars);
    if (isset($output_vars['id'])) {
        $id_kantin = intval($output_vars['id']);
    } elseif (isset($output_vars['ID'])) {
        $id_kantin = intval($output_vars['ID']);
    }
}

// JIKA TETAP GAGAL DAFTAR ID-NYA, KITA CETAK REALITY CHECK UNTUK DEBUG
if ($id_kantin <= 0) {
    echo "<h3>[Debug Mode] Terjadi kesalahan pembacaan sistem:</h3>";
    echo "Isi URL kamu saat ini: <b>" . htmlspecialchars($_SERVER['REQUEST_URI']) . "</b><br>";
    echo "Isi array \$_GET yang terbaca sistem: <pre>";
    print_r($_GET);
    echo "</pre>";
    die("Gagal memuat halaman. Silakan kembali ke halaman sebelumnya.");
}

// ==========================================
// 2. AMBIL NAMA KANTIN (Sama seperti kemarin)
// ==========================================
$nama_kantin = "Kantin";
$query_kantin = "SELECT NAMA_KANTIN FROM list_kantin WHERE ID = ?";
$stmt_k = $conn->prepare($query_kantin);
if ($stmt_k) {
    $stmt_k->bind_param("i", $id_kantin);
    $stmt_k->execute();
    $res_k = $stmt_k->get_result();
    if ($data_kantin = $res_k->fetch_assoc()) {
        $nama_kantin = $data_kantin['NAMA_KANTIN'];
    }
    $stmt_k->close();
}

// ==========================================
// 3. QUERY SEMUA TRANSAKSI OUTLET KANTIN INI
// ==========================================
$query_all_trx = "
    SELECT t.kode_pesanan, t.tgl, t.waktu, u.NAMA_LENGKAP as siswa, t.total, t.status
    FROM transaksi t
    JOIN users u ON t.id_user = u.ID
    WHERE t.id_kantin = ?
    ORDER BY t.tgl DESC, t.waktu DESC
";

$stmt_t = $conn->prepare($query_all_trx);
if (!$stmt_t) {
    die("Prepare query gagal: " . $conn->error);
}

$stmt_t->bind_param("i", $id_kantin);
$stmt_t->execute();
$result_transactions = $stmt_t->get_result();
$stmt_t->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Transaksi - <?php echo htmlspecialchars($nama_kantin); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #F8FAFC;
            color: #1E293B;
            padding: 40px 20px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .header-area {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #E2E8F0;
            color: #1E293B;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-back:hover {
            background: #FFF0E5;
            color: #F47B20;
            border-color: #F47B20;
            transform: translateX(-3px);
        }

        h2 {
            font-size: 24px;
            font-weight: 700;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .transaction-table th {
            font-size: 11px;
            font-weight: 700;
            color: #94A3B8;
            padding: 16px;
            border-bottom: 2px solid #E2E8F0;
            text-transform: uppercase;
        }

        .transaction-table td {
            padding: 16px;
            border-bottom: 1px solid #F1F5F9;
        }

        .tx-id {
            font-weight: 700;
            color: #F47B20;
        }

        /* Badge Status mengikuti teks database di video kamu */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: lowercase;
        }

        .status-success {
            background-color: #E6F7ED;
            color: #22C55E;
        }

        .status-pending {
            background-color: #FDF2E9;
            color: #D97706;
        }

        .status-danger {
            background-color: #FEE2E2;
            color: #EF4444;
        }

        .btn-detail {
            background-color: #FFF0E5;
            color: #F47B20;
            border: 1px solid #F47B20;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-detail:hover {
            background-color: #F47B20;
            color: white;
        }

        /* Styling Background Pop-up (Modal Overlay) */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15, 23, 42, 0.6); /* Backdrop agak gelap modern */
    backdrop-filter: blur(4px); /* Efek blur blur estetik */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0; pointer-events: none;
    transition: all 0.3s ease;
}

/* Ketika Modal Aktif */
.modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
}

/* Pembungkus Struk di dalam Pop-up */
.modal-content {
    background: #ffffff;
    width: 100%;
    max-width: 450px; /* Ukuran pas untuk struk nota */
    max-height: 85vh;
    overflow-y: auto;
    border-radius: 20px;
    padding: 30px;
    position: relative;
    transform: scale(0.9);
    transition: all 0.3s ease;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
}

.modal-overlay.active .modal-content {
    transform: scale(1);
}

/* Tombol Close Silang */
.close-modal {
    position: absolute;
    top: 20px; right: 20px;
    background: #F1F5F9;
    border: none;
    width: 32px; height: 32px;
    border-radius: 50%;
    font-size: 16px; font-weight: bold;
    cursor: pointer; color: #64748B;
    display: flex; align-items: center; justify-content: center;
    transition: 0.2s;
}
.close-modal:hover {
    background: #FFEFE5;
    color: #E06313;
}

/* Sembunyikan scrollbar bawaan modal biar rapi */
.modal-content::-webkit-scrollbar { width: 6px; }
.modal-content::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
    </style>
</head>

<body>

    <div class="container">
        <div class="header-area">
            <a href="editoutlet.php?id=<?php echo $id_kantin; ?>" class="btn-back">&#10094;</a>
            <div>
                <h2>Semua Riwayat Transaksi</h2>
                <p style="color: #64748B; font-size: 14px;"><?php echo htmlspecialchars($nama_kantin); ?> (ID: <?php echo $id_kantin; ?>)</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>KODE PESANAN</th>
                        <th>TANGGAL</th>
                        <th>WAKTU</th>
                        <th>SISWA</th>
                        <th>TOTAL PESANAN</th>
                        <th>STATUS</th>
                        <th style="text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_transactions->num_rows > 0): ?>
                        <?php while ($row_tx = $result_transactions->fetch_assoc()):
                            // Management warna badge status dinamis
                            $badge_class = 'status-pending';
                            $status_clean = strtolower($row_tx['status']);
                            
                            if ($status_clean === 'success' || $status_clean === 'selesai' || $status_clean === 'dikonfirmasi' || $status_clean === 'siap diambil') {
                                $badge_class = 'status-success';
                            } elseif ($status_clean === 'cancel' || $status_clean === 'dibatalkan' || $status_clean === 'ditolak') {
                                $badge_class = 'status-danger';
                            }
                        ?>
                            <tr>
                                <td class="tx-id">#<?php echo htmlspecialchars($row_tx['kode_pesanan']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row_tx['tgl'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row_tx['waktu'])); ?> WIB</td>
                                <td><?php echo htmlspecialchars($row_tx['siswa']); ?></td>
                                <td>Rp <?php echo number_format($row_tx['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars($row_tx['status']); ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-detail"
                                        onclick="openDetailModal('<?php echo $row_tx['kode_pesanan']; ?>')">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748B; padding: 30px;">
                                Kantin ini belum memiliki riwayat transaksi sama sekali.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<div id="detailModal" class="modal-overlay">
    <div class="modal-content">
        <button class="close-modal" onclick="closeDetailModal()">&times;</button>
        
        <div class="nota-card" style="border:none; padding:0; box-shadow:none;">
            <div class="nota-header">
                <h2>DETAIL PESANAN</h2>
                <h3 style="font-size: 18px; margin-top: 10px; color:#111;">Kantin Kita</h3>
                <p>Rincian Transaksi Pembelian</p>
            </div>

            <div id="modalDynamicContent">
                <p style="text-align:center; color:#888; padding:20px;">Memuat data transaksi...</p>
            </div>
        </div>
        
    </div>
</div>

    <script>
       function openDetailModal(kodePesanan) {
    const modal = document.getElementById('detailModal');
    const contentArea = document.getElementById('modalDynamicContent');
    
    modal.classList.add('active');
    contentArea.innerHTML = '<p style="text-align:center; color:#888; padding:20px;">Memuat data transaksi...</p>';

    // JALUR BARU: Menggunakan `./process/...` agar dia mutlak mencari di dalam folder tempat transaksi.php berada
    fetch('./process/detail_pesanan.php?id=' + kodePesanan)
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP error, status = " + response.status);
            }
            return response.text();
        })
        .then(htmlData => {
            contentArea.innerHTML = htmlData;
        })
        .catch(err => {
            contentArea.innerHTML = '<p style="text-align:center; color:red; padding:20px;">Gagal memuat detail nota. File tidak ditemukan.</p>';
        });
}

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        // Close otomatis jika mengklik area hitam di luar kertas struk
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                modal.classList.remove('active');
            }
        }
    </script>
</body>

</html>
<?php
$conn->close();
?>