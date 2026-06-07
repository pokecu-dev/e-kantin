<?php

// session_start();
require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/adminC.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID kantin gak ada. Tambahin ?id=1");
}

$id_kantin = intval($_GET['id']);

$nama_kantin = "Kantin Kita";
$status_kantin = "0";
$nama_pemilik_kantin = "Tidak Diketahui";

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

$hari_ini = date('Y-m-d');

$query_rev = "
SELECT SUM(total) as total_rev, COUNT(*) as total_trx
FROM transaksi
WHERE id_kantin = ?
AND DATE(tgl) = ?
AND status IN ('diproses', 'dikonfirmasi', 'pending', 'selesai', 'success','siap diambil')
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


$grafik_minggu = array_fill(0, 5, 0);
$labels_minggu = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"];

$q_g_minggu = "SELECT WEEKDAY(tgl) as hari, SUM(total) as total FROM transaksi WHERE id_kantin = ? AND YEARWEEK(tgl, 1) = YEARWEEK(CURDATE(), 1) AND WEEKDAY(tgl) BETWEEN 0 AND 4 AND status IN ('diproses', 'dikonfirmasi', 'pending', 'selesai', 'success', 'siap diambil') GROUP BY WEEKDAY(tgl)";
$stmt_g1 = $conn->prepare($q_g_minggu);
$stmt_g1->bind_param("i", $id_kantin);
$stmt_g1->execute();
$res_g1 = $stmt_g1->get_result();
while ($row = $res_g1->fetch_assoc()) {
    $grafik_minggu[$row['hari']] = (int)$row['total'];
}
$stmt_g1->close();

$grafik_bulan = array_fill(0, 12, 0);
$labels_bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];

$q_g_bulan = "SELECT MONTH(tgl) as bulan, SUM(total) as total FROM transaksi WHERE id_kantin = ? AND YEAR(tgl) = YEAR(CURDATE()) AND status IN ('diproses', 'dikonfirmasi', 'pending', 'selesai', 'success', 'siap diambil') GROUP BY MONTH(tgl)";
$stmt_g2 = $conn->prepare($q_g_bulan);
$stmt_g2->bind_param("i", $id_kantin);
$stmt_g2->execute();
$res_g2 = $stmt_g2->get_result();
while ($row = $res_g2->fetch_assoc()) {
    $grafik_bulan[$row['bulan'] - 1] = (int)$row['total'];
}
$stmt_g2->close();

$tahun_sekarang = (int)date('Y');
$grafik_tahun = [];
$labels_tahun = [];
for ($i = 6; $i >= 0; $i--) {
    $t = $tahun_sekarang - $i;
    $labels_tahun[] = $t;
    $grafik_tahun[$t] = 0;
}

$q_g_tahun = "SELECT YEAR(tgl) as tahun, SUM(total) as total FROM transaksi WHERE id_kantin = ? AND YEAR(tgl) >= (? - 6) AND status IN ('diproses', 'dikonfirmasi', 'pending', 'selesai', 'success', 'siap diambil') GROUP BY YEAR(tgl)";
$stmt_g3 = $conn->prepare($q_g_tahun);
$stmt_g3->bind_param("ii", $id_kantin, $tahun_sekarang);
$stmt_g3->execute();
$res_g3 = $stmt_g3->get_result();
while ($row = $res_g3->fetch_assoc()) {
    if (isset($grafik_tahun[$row['tahun']])) {
        $grafik_tahun[$row['tahun']] = (int)$row['total'];
    }
}
$stmt_g3->close();

$data_tahun_values = array_values($grafik_tahun);


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

$query_menu = "
    SELECT 
        m.*, 
        m.id_menu AS ID_MENU, 
        IFNULL(AVG(r.RATING), 0) AS RATING_RATA
    FROM tb_menu m
    LEFT JOIN rating r ON m.id_menu = r.ID_MENU
    WHERE m.id_kantin = '$id_kantin'
    GROUP BY m.id_menu
";
$result_menu = $conn->query($query_menu);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nama_kantin); ?> - Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            --border-color: #E2E8F0;
        }

        body {
            background-color: var(--bg-global);
            color: var(--text-main);
            padding-bottom: 50px;
        }

        /* Hilangkan Scrollbar sesuai gaya kode kamu */
        ::-webkit-scrollbar {
            width: 0px !important;
            background: transparent !important;
        }
        html, body, *, div {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
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

        /* Status Bar Card */
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

        .indicator-active { background-color: #E6F7ED; color: #22C55E; }
        .indicator-inactive { background-color: #FEE2E2; color: #EF4444; }

        .btn-toggle-status {
            padding: 8px 20px;
            border: none;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        .btn-deactivate { background-color: #EF4444; color: white; }
        .btn-deactivate:hover { background-color: #DC2626; }
        .btn-activate { background-color: #10B981; color: white; }
        .btn-activate:hover { background-color: #059669; }

        .status-bar-right {
            font-size: 13px;
            color: var(--text-muted);
            text-align: right;
        }

        /* Dropdown Filter Model Sesuai Permintaan */
        .chart-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
            border-color: var(--primary-color);
        }

        /* Products Layout */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }
        .product-card { padding: 0; overflow: hidden; display: flex; flex-direction: column; }
        .product-img-wrapper { position: relative; width: 100%; height: 160px; background-color: #EEE; }
        .product-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        
        .badge { position: absolute; top: 12px; right: 12px; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .badge-available { background-color: #D1FAE5; color: #065F46; }
        .badge-empty { background-color: #FEE2E2; color: #991B1B; }
        
        .product-info { padding: 16px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .product-rating { display: flex; align-items: center; gap: 3px; margin: 5px 0; font-size: 12px; }
        .rating-text { color: var(--text-muted); font-size: 11px; margin-left: 2px; }
        .product-price { color: var(--primary-color); font-weight: 700; }

        /* Table Layout */
        .transaction-section { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color); padding: 24px; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .transaction-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .transaction-table th { font-size: 11px; font-weight: 700; color: #94A3B8; padding: 16px; border-bottom: 1px solid var(--border-color); }
        .transaction-table td { padding: 18px 16px; border-bottom: 1px solid #F1F5F9; }
        
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-success { background-color: #E6F7ED; color: #22C55E; }
        .status-pending { background-color: #FDF2E9; color: #D97706; }
        .status-danger { background-color: #FEE2E2; color: #EF4444; }
        .tx-id { font-weight: 700; color: #F47B20; }
    </style>
</head>

<body>

    <div class="dashboard-wrapper">

        <div class="status-bar-card">
            <div class="status-bar-left">
                <?php if ($status_kantin === '1' || $status_kantin == 1): ?>
                    <div class="status-indicator indicator-active">● Kantin Aktif</div>
                    <form action="process/update_status.php" method="POST">
                        <input type="hidden" name="id_kantin" value="<?php echo $id_kantin; ?>">
                        <input type="hidden" name="status" value="0">
                        <button type="submit" class="btn-toggle-status btn-deactivate">Nonaktifkan Kantin</button>
                    </form>
                <?php else: ?>
                    <div class="status-indicator indicator-inactive">● Kantin Nonaktif</div>
                    <form action="process/update_status.php" method="POST">
                        <input type="hidden" name="id_kantin" value="<?php echo $id_kantin; ?>">
                        <input type="hidden" name="status" value="1">
                        <button type="submit" class="btn-toggle-status btn-activate">Aktifkan Kantin</button>
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
                    <h2>Laporan (<?php echo htmlspecialchars($nama_kantin); ?>)</h2>
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

        <section class="chart-card">
            <div class="chart-title-row">
                <h3 style="font-size:16px; font-weight:700; color:#1a202c;">Tren Pendapatan Outlet</h3>
                <select id="filterTrend" class="select-filter-dropdown" onchange="gantiSumbuGrafik(this.value)">
                    <option value="minggu">Minggu Ini</option>
                    <option value="bulan">Bulan Ini</option>
                    <option value="tahun">Tahun Ini</option>
                </select>
            </div>
            <div style="position: relative; width: 100%; height: 280px;">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </section>

        <section class="transaction-section">
            <div class="section-header">
                <h2>Riwayat Transaksi Outlet Terbaru</h2>
                <a href="transaksi.php?id=<?php echo $id_kantin; ?>" style="color:var(--primary-color); text-decoration:none; font-size:14px; font-weight:600;">Lihat Semua →</a>
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
                                if (in_array(strtolower($row_tx['status']), ['success', 'selesai'])) $badge_class = 'status-success';
                                if (in_array(strtolower($row_tx['status']), ['cancel', 'dibatalkan'])) $badge_class = 'status-danger';
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
                <?php if ($result_menu && $result_menu->num_rows > 0): ?>
                    <?php while ($row_menu = $result_menu->fetch_assoc()):
                        $is_habis = (strtolower($row_menu['STOK']) === 'habis' || $row_menu['STOK'] == 0);
                        $foto = !empty($row_menu['FOTO_MENU']) ? $row_menu['FOTO_MENU'] : 'default.jpg';
                        $foto = ltrim($foto);
                        
                        $rating_asli = floatval($row_menu['RATING_RATA']);
                        $id_menu_js = intval($row_menu['ID_MENU']);
                    ?>
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <img src="./../../source/gambar_menu/<?= htmlspecialchars($foto) ?>" alt="Menu">
                                <?php if (!$is_habis): ?>
                                    <span class="badge badge-available">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge badge-empty">Habis</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div>
                                    <h3 style="font-size:16px; margin-bottom:2px; color:var(--text-main);"><?php echo htmlspecialchars($row_menu['NAMA_MENU']); ?></h3>
                                    
                                    <div class="product-rating" style="margin-bottom: 4px;">
                                        <?php 
                                        $bintang_aktif = round($rating_asli);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $bintang_aktif) {
                                                echo '<i class="fas fa-star" style="color: #F47B20;"></i>';
                                            } else {
                                                echo '<i class="far fa-star" style="color: #cbd5e1;"></i>';
                                            }
                                        }
                                        ?>
                                        <span class="rating-text">(<?= number_format($rating_asli, 1) ?>)</span>
                                    </div>
                                    <p class="product-price" style="margin-bottom: 12px;">Rp <?php echo number_format($row_menu['HARGA'], 0, ',', '.'); ?></p>
                                </div>
                                
                                <button type="button" onclick="bukaModalUlasan(<?= $id_menu_js ?>)" style="width: 100%; padding: 8px; border: 1px solid #F47B20; background: white; color: #F47B20; border-radius: 8px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#F47B20'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#F47B20';">
                                    <i class="fas fa-comments"></i> Lihat Ulasan
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color:var(--text-muted); grid-column:1/-1;">Belum ada produk/menu yang didaftarkan pada kantin ini.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <div id="modalUlasan" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; pointer-events: none; transition: all 0.3s ease;">
        <div style="background: white; padding: 24px; border-radius: 24px; width: 90%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative;">
            <button onclick="tutupModalUlasan()" style="position: absolute; top: 16px; right: 16px; background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; color: #64748b;">
                <i class="fas fa-times"></i>
            </button>
            <div id="kontenUlasan"></div>
        </div>
    </div>
<script>
  
    const modalUlasan = document.getElementById('modalUlasan');
    const kontenUlasan = document.getElementById('kontenUlasan');

    function bukaModalUlasan(idMenu) {
        modalUlasan.style.opacity = '1';
        modalUlasan.style.pointerEvents = 'auto';
        kontenUlasan.innerHTML = `<div style="text-align:center; padding:30px; color:#64748b;"><i class="fas fa-spinner fa-spin"></i> Memuat ulasan...</div>`;

        fetch(`get_ulasan.php?id=${idMenu}`)
            .then(response => response.text())
            .then(html => {
                kontenUlasan.innerHTML = html;
            })
            .catch(error => {
                kontenUlasan.innerHTML = `<p style="color:red; text-align:center; padding:20px;">Gagal memuat ulasan.</p>`;
            });
    }

    function tutupModalUlasan() {
        modalUlasan.style.opacity = '0';
        modalUlasan.style.pointerEvents = 'none';
    }

    window.addEventListener('click', function(e) {
        if (e.target === modalUlasan) {
            tutupModalUlasan();
        }
    });

    
    const dataGrafikMaster = {
        minggu: {
            labels: <?php echo json_encode($labels_minggu); ?>,
            data: <?php echo json_encode($grafik_minggu); ?>
        },
        bulan: {
            labels: <?php echo json_encode($labels_bulan); ?>,
            data: <?php echo json_encode($grafik_bulan); ?>
        },
        tahun: {
            labels: <?php echo json_encode($labels_tahun); ?>,
            data: <?php echo json_encode($data_tahun_values); ?>
        }
    };

    let instanceChart = null;

    
    function renderEngineGrafik(tipeSumbu) {
        const canvasCtx = document.getElementById('chartPendapatan').getContext('2d');
        
        if (!instanceChart) {
            instanceChart = new Chart(canvasCtx, {
                type: 'bar',
                data: {
                    labels: dataGrafikMaster[tipeSumbu].labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: dataGrafikMaster[tipeSumbu].data,
                        backgroundColor: '#F47B20',
                        borderRadius: 8,
                        barThickness: window.innerWidth < 768 ? 16 : 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animations: {
                        y: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        properties: ['x', 'y', 'borderWidth', 'backgroundColor']
                    },
                    transitions: {
                        active: {
                            animation: {
                                duration: 800
                            }
                        }
                    },
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F5F9' },
                            ticks: {
                                callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); },
                                font: { family: 'Poppins', size: 11 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Poppins', size: 11 } }
                        }
                    }
                }
            });
        } 
        else {
            instanceChart.data.labels = dataGrafikMaster[tipeSumbu].labels;
            instanceChart.data.datasets[0].data = dataGrafikMaster[tipeSumbu].data;
            
            instanceChart.update('active'); 
        }
    }

    
    document.addEventListener("DOMContentLoaded", function() {
        // Render default grafik pertama kali (minggu)
        renderEngineGrafik('minggu');

        const elemenSelectFilter = document.getElementById('filterTrend');
        
        if (elemenSelectFilter) {
            elemenSelectFilter.addEventListener('change', function() {
                renderEngineGrafik(this.value);
            });
        }
    });
    // 🔥 FUNGSI UTAMA: Proses Hapus Ulasan (POST ke get_ulasan.php)
function prosesHapusUlasan(idUlasan, idMenu) {
    // 1. Munculin konfirmasi awal pas tombol diklik
    if (confirm("Apakah Anda yakin ingin menghapus ulasan ini?")) {
        
        // Bungkus data ke dalam FormData agar terbaca sebagai $_POST di PHP
        const formData = new FormData();
        formData.append('aksi', 'hapus');
        formData.append('id_ulasan', idUlasan);

        // Tembak ke file yang sama (get_ulasan.php) menggunakan metode POST
        fetch(`get_ulasan.php`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // 2. Munculkan toast notification kalau sukses
                tampilkanToast("Ulasan dihapus");
                
                // 3. Refresh isi modal secara realtime biar ulasan yang dihapus langsung hilang dari list
                bukaModalUlasan(idMenu);
            } else {
                alert("Gagal menghapus ulasan: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Terjadi kesalahan sistem saat menghapus ulasan.");
        });
    }
}

// 🌟 FUNGSI HELPER: Bikin Toast Notif Melayang yang Otomatis Hilang Sendiri
function tampilkanToast(pesan) {
    // Buat elemen div baru untuk toast
    const toast = document.createElement("div");
    toast.textContent = pesan;
    
    // Styling langsung lewat JS biar estetik melayang di kanan atas dengan warna merah soft / merah info
    Object.assign(toast.style, {
        position: "fixed",
        top: "20px",
        right: "20px",
        backgroundColor: "#ef4444", // Warna merah cerah (Tailwind red-500)
        color: "white",
        padding: "12px 24px",
        borderRadius: "8px",
        boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
        fontFamily: "'Poppins', sans-serif",
        fontSize: "14px",
        fontWeight: "500",
        zIndex: "10000",
        transition: "opacity 0.4s ease, transform 0.4s ease",
        opacity: "0",
        transform: "translateY(-10px)"
    });

    document.body.appendChild(toast);

    // Efek transisi masuk (Fade In + geser ke bawah dikit)
    setTimeout(() => {
        toast.style.opacity = "1";
        toast.style.transform = "translateY(0)";
    }, 50);

    // Efek transisi keluar (Fade Out) & hapus elemen setelah 2.5 detik
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(-10px)";
        setTimeout(() => toast.remove(), 400); // benar-benar hilang dari struktur HTML
    }, 2500);

}
</script>
    </script>
</body>
</html>
<?php
$conn->close();
?>