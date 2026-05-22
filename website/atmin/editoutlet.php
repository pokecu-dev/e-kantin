<?php

session_start();
require_once __DIR__ . "/../include/koneksi.php";

// =======================
// DEBUG MODE (aktif biar gak silent error)
// =======================
ini_set('display_errors', 1);
error_reporting(E_ALL);



// =======================
// VALIDASI ID KANTIN
// =======================
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID kantin gak ada. Tambahin ?id=1");
}

$id_kantin = intval($_GET['id']);

// =======================
// DATA DEFAULT
// =======================
$nama_kantin = "Kantin Kita";
$status_kantin = "0";
$nama_pemilik_kantin = "Tidak Diketahui";

// =======================
// 1. DATA KANTIN
// =======================
$query_kantin = "
SELECT l.ID, l.NAMA_KANTIN, l.STATUS, u.NAMA_LENGKAP AS NAMA_PENJUAL
FROM list_kantin l
LEFT JOIN users u ON l.id_penjual = u.ID
WHERE l.ID = ?
";

$stmt_k = $conn->prepare($query_kantin);
if (!$stmt_k) die("Prepare kantin gagal: " . $conn->error);

$stmt_k->bind_param("i", $id_kantin);
$stmt_k->execute();
$res_k = $stmt_k->get_result();

if ($res_k->num_rows == 0) {
    die("Kantin tidak ditemukan.");
}

$data_k = $res_k->fetch_assoc();

$nama_kantin = $data_k['NAMA_KANTIN'];
$status_kantin = $data_k['STATUS'];
$nama_pemilik_kantin = $data_k['NAMA_PENJUAL'] ?: "Belum Ada Penjual";

$stmt_k->close();

// =======================
// 2. REVENUE HARI INI
// =======================
$hari_ini = date('Y-m-d');

$query_rev = "
SELECT SUM(total) as total_rev, COUNT(*) as total_trx
FROM transaksi
WHERE id_kantin = ?
AND DATE(tgl) = ?
AND status = 'success'
";

$stmt_r = $conn->prepare($query_rev);
if (!$stmt_r) die("Prepare revenue gagal: " . $conn->error);

$stmt_r->bind_param("is", $id_kantin, $hari_ini);
$stmt_r->execute();
$res_rev = $stmt_r->get_result()->fetch_assoc();

$total_revenue = $res_rev['total_rev'] ?? 0;
$total_transactions = $res_rev['total_trx'] ?? 0;
$avg_order_value = $total_transactions > 0 ? round($total_revenue / $total_transactions) : 0;

$stmt_r->close();

// =======================
// 3. TRANSAKSI TERBARU (DIBATASI BIAR GAK LAG)
// =======================
$query_trx_list = "
SELECT t.kode_pesanan, t.waktu, u.NAMA_LENGKAP as siswa, t.total, t.status
FROM transaksi t
JOIN users u ON t.id_user = u.ID
WHERE t.id_kantin = ?
ORDER BY t.tgl DESC, t.waktu DESC
LIMIT 5
";

$stmt_tl = $conn->prepare($query_trx_list);
if (!$stmt_tl) die("Prepare transaksi gagal: " . $conn->error);

$stmt_tl->bind_param("i", $id_kantin);
$stmt_tl->execute();
$result_transactions = $stmt_tl->get_result();
$stmt_tl->close();

// =======================
// 4. MENU
// =======================
$query_menu = "
SELECT NAMA_MENU, HARGA, STOK, FOTO_MENU
FROM tb_menu
WHERE ID_KANTIN = ?
";

$stmt_m = $conn->prepare($query_menu);
if (!$stmt_m) die("Prepare menu gagal: " . $conn->error);

$stmt_m->bind_param("i", $id_kantin);
$stmt_m->execute();
$result_menu = $stmt_m->get_result();
$stmt_m->close();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nama_kantin); ?> - Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #F47B20;
            --primary-light: #FFF0E5;
            --bg-global: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --green-trend: #10B981;
            --red-trend: #EF4444;
            --border-color: #E2E8F0;
        }

        body {
            background-color: var(--bg-global);
            color: var(--text-main);
            padding-bottom: 50px;
        }

        .dashboard-wrapper {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .stat-card,
        .chart-card,
        .product-card,
        .status-bar-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 24px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .header-title-container {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-back:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateX(-3px);
        }

        .section-header h2 {
            font-size: 22px;
            font-weight: 700;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 700;
            color: #94A3B8;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            margin: 8px 0;
        }

        /* Bar Atas Status & Sesi Kontrol */
        .status-bar-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 16px 24px;
        }

        .status-bar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 14px;
            padding: 6px 16px;
            border-radius: 30px;
        }

        .indicator-active {
            background-color: #E6F7ED;
            color: #22C55E;
        }

        .indicator-inactive {
            background-color: #FEE2E2;
            color: #EF4444;
        }

        .btn-toggle-status {
            padding: 8px 20px;
            border: none;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }

        .btn-deactivate {
            background-color: #EF4444;
            color: white;
        }

        .btn-deactivate:hover {
            background-color: #DC2626;
        }

        .btn-activate {
            background-color: #10B981;
            color: white;
        }

        .btn-activate:hover {
            background-color: #059669;
        }

        .status-bar-right {
            font-size: 13px;
            color: var(--text-muted);
            text-align: right;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .product-card {
            padding: 0;
            overflow: hidden;
        }

        .product-img-wrapper {
            position: relative;
            width: 100%;
            height: 160px;
            background-color: #EEE;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-available {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .badge-empty {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .product-info {
            padding: 16px;
        }

        .product-price {
            color: var(--primary-color);
            font-weight: 700;
        }

        .transaction-section {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 24px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
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
            border-bottom: 1px solid var(--border-color);
        }

        .transaction-table td {
            padding: 18px 16px;
            border-bottom: 1px solid #F1F5F9;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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

        .tx-id {
            font-weight: 700;
            color: #F47B20;
        }
    </style>
</head>

<body>

    <div class="dashboard-wrapper">

        <div class="status-bar-card">
            <div class="status-bar-left">
                <?php if ($status_kantin === '1' || $status_kantin == 1): ?>

                    <div class="status-indicator indicator-active">
                        ● Kantin Aktif
                    </div>

                    <form action="process/update_status.php" method="POST">

                        <input
                            type="hidden"
                            name="id_kantin"
                            value="<?php echo $id_kantin; ?>">

                        <input
                            type="hidden"
                            name="status"
                            value="0">

                        <button
                            type="submit"
                            class="btn-toggle-status btn-deactivate">
                            Nonaktifkan Kantin
                        </button>

                    </form>

                <?php else: ?>

                    <div class="status-indicator indicator-inactive">
                        ● Kantin Nonaktif
                    </div>

                    <form action="process/update_status.php" method="POST">

                        <input
                            type="hidden"
                            name="id_kantin"
                            value="<?php echo $id_kantin; ?>">

                        <input
                            type="hidden"
                            name="status"
                            value="1">

                        <button
                            type="submit"
                            class="btn-toggle-status btn-activate">
                            Buka Kantin
                        </button>

                    </form>

                <?php endif; ?>
            </div>

            <div class="status-bar-right">
                Yang punya kantin: <strong><?php echo htmlspecialchars($nama_pemilik_kantin); ?></strong>
                <span style="color:var(--text-muted); font-size:11px;">(ID Kantin: <?php echo $id_kantin; ?>)</span>
            </div>
        </div>

        <section class="report-section">
            <div class="section-header">
                <div class="header-title-container">
                    <a href="oulet.php" class="btn-back" title="Kembali Ke Daftar Pilihan Outlet">&#10094;</a>
                    <h2>Laporan Hari Ini (<?php echo htmlspecialchars($nama_kantin); ?>)</h2>
                </div>
                <span class="report-date">📅 <?php echo date('d M Y'); ?></span>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-label">TOTAL REVENUE</span>
                    <h3 class="stat-value">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></h3>
                    <span class="stat-trend trend-up" style="color:var(--green-trend);">↑ Real-time DB</span>
                </div>

                <div class="stat-card">
                    <span class="stat-label">TOTAL TRANSACTIONS</span>
                    <h3 class="stat-value"><?php echo number_format($total_transactions, 0, ',', '.'); ?></h3>
                    <span class="stat-trend trend-up" style="color:var(--green-trend);">↑ Transaksi Berhasil</span>
                </div>

                <div class="stat-card">
                    <span class="stat-label">AVG. ORDER VALUE</span>
                    <h3 class="stat-value">Rp <?php echo number_format($avg_order_value, 0, ',', '.'); ?></h3>
                    <span class="stat-trend trend-down" style="color:var(--primary-color);">Calculated</span>
                </div>
            </div>
        </section>

        <section class="transaction-section">
            <div class="section-header">
                <h2>Riwayat Transaksi Outlet Terbaru</h2>
                <a href="#" style="color:var(--primary-color); text-decoration:none; font-size:14px; font-weight:600;">Lihat Semua →</a>
            </div>
            <div class="table-responsive">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>KODE PESANAN</th>
                            <th>WAKTU</th>
                            <th>SISWA</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_transactions->num_rows > 0): ?>
                            <?php while ($row_tx = $result_transactions->fetch_assoc()):
                                $badge_class = 'status-pending';
                                if (strtolower($row_tx['status']) === 'success' || strtolower($row_tx['status']) === 'selesai') $badge_class = 'status-success';
                                if (strtolower($row_tx['status']) === 'cancel' || strtolower($row_tx['status']) === 'dibatalkan') $badge_class = 'status-danger';
                            ?>
                                <tr>
                                    <td class="tx-id">#<?php echo htmlspecialchars($row_tx['kode_pesanan']); ?></td>
                                    <td><?php echo date('H:i', strtotime($row_tx['waktu'])); ?></td>
                                    <td><?php echo htmlspecialchars($row_tx['siswa']); ?></td>
                                    <td>Rp <?php echo number_format($row_tx['total'], 0, ',', '.'); ?></td>
                                    <td><span class="status-badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($row_tx['status']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:var(--text-muted);">Belum ada data transaksi hari ini pada kantin ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="products-section">
            <div class="section-header">
                <h2>Daftar Produk Kantin</h2>
            </div>
            <div class="products-grid">
                <?php if ($result_menu->num_rows > 0): ?>
                    <?php while ($row_menu = $result_menu->fetch_assoc()):
                        $is_habis = (strtolower($row_menu['STOK']) === 'habis' || $row_menu['STOK'] == 0);
                        $foto = !empty($row_menu['FOTO_MENU']) ? $row_menu['FOTO_MENU'] : 'default.jpg';
                    ?>
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <img src="../../source/foto_menu/<?php echo htmlspecialchars($foto); ?>" onerror="this.src='https://via.placeholder.com/300x180?text=Menu+Kantin'" alt="Menu">
                                <?php if (!$is_habis): ?>
                                    <span class="badge badge-available">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge badge-empty">Habis</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 style="font-size:16px; margin-bottom:6px;"><?php echo htmlspecialchars($row_menu['NAMA_MENU']); ?></h3>
                                <p class="product-price">Rp <?php echo number_format($row_menu['HARGA'], 0, ',', '.'); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color:var(--text-muted); grid-column:1/-1;">Belum ada produk/menu yang didaftarkan pada kantin ini.</p>
                <?php endif; ?>
            </div>
        </section>

    </div>
</body>

</html>
<?php
$conn->close();
?>