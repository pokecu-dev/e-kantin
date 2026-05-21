<?php

require_once __DIR__ . "/../include/koneksi.php";
// require_once __DIR__ . "/../include/session/loginCheck.php";

if ($conn->error) {
    echo $conn->connect_error;
}
// --- MOCK SESSION FOR TESTING ---
// Hapus atau comment 3 baris di bawah ini jika sistem login riil Anda sudah diimplementasikan
$_SESSION['user_id'] = 24; 
$_SESSION['role']    = 'PENJUAL';
$_SESSION['nama']    = 'Pak Trisno';
// ---------------------------------

// Proteksi Dashboard
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['PENJUAL', 'ADMIN'])) {
    die("Akses ditolak. Anda harus login sebagai Penjual atau Admin.");
}

$current_user_id = $_SESSION['user_id'];

// ==========================================
// DATA FETCHING: 1. MENDAPATKAN DATA KANTIN
// ==========================================
$id_kantin = 0;
$nama_kantin = "Kantin Kita";
$status_kantin = "0";

$query_kantin = "SELECT id, NAMA_KANTIN, STATUS FROM list_kantin WHERE id_penjual = ?";
$stmt_k = $conn->prepare($query_kantin);
$stmt_k->bind_param("i", $current_user_id);
$stmt_k->execute();
$res_k = $stmt_k->get_result();
if ($data_k = $res_k->fetch_assoc()) {
    $id_kantin     = $data_k['id'];
    $nama_kantin   = $data_k['NAMA_KANTIN'];
    $status_kantin = $data_k['STATUS']; // '1' atau '0'
}
$stmt_k->close();

// ==========================================
// DATA FETCHING: 2. AGREGASI LAPORAN HARI INI
// ==========================================
$hari_ini = date('Y-md');

// Hitung total revenue hari ini untuk kantin ini (Hanya transaksi bertatus 'selesai' atau 'success')
// Catatan: sesuaikan string status 'success' atau 'selesai' dengan value riil sistem Anda
$query_rev = "SELECT SUM(total) as total_rev, COUNT(id) as total_trx FROM transaksi WHERE id_kantin = ? AND tgl = ? AND status = 'success'";
$stmt_r = $conn->prepare($query_rev);
$stmt_r->bind_param("is", $id_kantin, $hari_ini);
$stmt_r->execute();
$res_rev = $stmt_r->get_result()->fetch_assoc();

$total_revenue      = $res_rev['total_rev'] ?? 0;
$total_transactions = $res_rev['total_trx'] ?? 0;
$avg_order_value    = $total_transactions > 0 ? round($total_revenue / $total_transactions) : 0;
$stmt_r->close();

// ==========================================
// DATA FETCHING: 3. DAFTAR TRANSAKSI RECENT
// ==========================================
// Melakukan JOIN antara transaksi dan users untuk mendapatkan nama siswa/pembeli
$query_trx_list = "SELECT t.kode_pesanan, t.waktu, u.NAMA_LENGKAP as siswa, t.total, t.status, t.catatan 
                   FROM transaksi t 
                   JOIN users u ON t.id_user = u.ID 
                   WHERE t.id_kantin = ? 
                   ORDER BY t.tgl DESC, t.waktu DESC";
$stmt_tl = $conn->prepare($query_trx_list);
$stmt_tl->bind_param("i", $id_kantin);
$stmt_tl->execute();
$result_transactions = $stmt_tl->get_result();

// ==========================================
// DATA FETCHING: 4. DAFTAR MENU MAKANAN
// ==========================================
$query_menu = "SELECT NAMA_MENU, HARGA, STOK, FOTO_MENU FROM tb_menu WHERE ID_KANTIN = ?";
$stmt_m = $conn->prepare($query_menu);
$stmt_m->bind_param("i", $id_kantin);
$stmt_m->execute();
$result_menu = $stmt_m->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nama_kantin); ?> - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* [Gaya CSS tetap dipertahankan sesuai dengan layout awal Anda] */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        :root {
            --primary-color: #FF6B00; --primary-light: #FFF0E5; --bg-global: #F8F9FA;
            --card-bg: #FFFFFF; --text-main: #1A1A1A; --text-muted: #7A7A7A;
            --green-trend: #10B981; --red-trend: #EF4444; --border-color: #ECECEC;
        }
        body { background-color: var(--bg-global); color: var(--text-main); padding-bottom: 50px; }
        .dashboard-wrapper { display: grid; grid-template-columns: 2.5fr 1fr; gap: 30px; max-width: 1400px; margin: 30px auto; padding: 0 30px; }
        .stat-card, .chart-card, .sidebar-card, .product-card, .status-control-card { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color); padding: 24px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-header h2 { font-size: 20px; font-weight: 700; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-label { font-size: 11px; font-weight: 700; color: #9CA3AF; letter-spacing: 0.5px; }
        .stat-value { font-size: 22px; font-weight: 700; margin: 8px 0; }
        .chart-card { margin-bottom: 35px; }
        .chart-bars { display: flex; justify-content: space-between; align-items: flex-end; height: 150px; border-bottom: 1px solid var(--border-color); }
        .bar-container { display: flex; flex-direction: column; align-items: center; width: 10%; }
        .bar { width: 80%; border-radius: 4px 4px 0 0; background-color: var(--primary-color); }
        .bar-label { font-size: 10px; color: var(--text-muted); margin-top: 8px; }
        .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .product-card { padding: 0; overflow: hidden; }
        .product-img-wrapper { position: relative; width: 100%; height: 160px; background-color: #EEE; }
        .product-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .badge { position: absolute; top: 12px; right: 12px; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .badge-available { background-color: #D1FAE5; color: #065F46; }
        .badge-empty { background-color: #FEE2E2; color: #991B1B; }
        .product-info { padding: 16px; }
        .product-price { color: var(--primary-color); font-weight: 700; }
        .sidebar-content { display: flex; flex-direction: column; gap: 24px; }
        
        /* STATUS CONTROL PANEL DESIGN */
        .status-control-card { text-align: center; border: 2px solid var(--border-color); }
        .status-indicator { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; margin-bottom: 15px; padding: 6px 16px; border-radius: 30px; }
        .indicator-active { background-color: #E6F7ED; color: #22C55E; }
        .indicator-inactive { background-color: #FEE2E2; color: #EF4444; }
        .btn-toggle-status { width: 100%; padding: 12px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; font-size: 13px; transition: all 0.2s; }
        .btn-deactivate { background-color: #EF4444; color: white; }
        .btn-deactivate:hover { background-color: #DC2626; }
        .btn-activate { background-color: #10B981; color: white; }
        .btn-activate:hover { background-color: #059669; }

        .transaction-section { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color); padding: 24px; margin-bottom: 35px; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .transaction-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .transaction-table th { font-size: 11px; font-weight: 700; color: #9CA3AF; padding: 16px; border-bottom: 1px solid var(--border-color); }
        .transaction-table td { padding: 18px 16px; border-bottom: 1px solid #F3F4F6; }
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-success { background-color: #E6F7ED; color: #22C55E; }
        .status-pending { background-color: #FDF2E9; color: #D97706; }
        .status-danger { background-color: #FEE2E2; color: #EF4444; }
        .tx-id { font-weight: 700; color: #B25E29; }
        @media (max-width: 1024px) { .dashboard-wrapper { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        
        <main class="main-content">
            
            <section class="report-section">
                <div class="section-header">
                    <h2>Laporan Hari Ini (<?php echo htmlspecialchars($nama_kantin); ?>)</h2>
                    <span class="report-date">📅 <?php echo date('d M Y'); ?></span>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-label">TOTAL REVENUE</span>
                        <h3 class="stat-value">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></h3>
                        <span class="stat-trend trend-up">↑ Real-time DB</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">TOTAL TRANSACTIONS</span>
                        <h3 class="stat-value"><?php echo number_format($total_transactions, 0, ',', '.'); ?></h3>
                        <span class="stat-trend trend-up">↑ Transaksi Berhasil</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">AVG. ORDER VALUE</span>
                        <h3 class="stat-value">Rp <?php echo number_format($avg_order_value, 0, ',', '.'); ?></h3>
                        <span class="stat-trend trend-down">Calculated</span>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header"><h4>Tren Penjualan Mingguan</h4></div>
                    <div class="chart-bars">
                        <div class="bar-container"><div class="bar" style="height: 45%;"></div><span class="bar-label">Sen</span></div>
                        <div class="bar-container"><div class="bar" style="height: 60%;"></div><span class="bar-label">Sel</span></div>
                        <div class="bar-container"><div class="bar" style="height: 75%;"></div><span class="bar-label">Rab</span></div>
                        <div class="bar-container"><div class="bar" style="height: 90%;"></div><span class="bar-label">Kam</span></div>
                        <div class="bar-container"><div class="bar" style="height: 50%;"></div><span class="bar-label">Jum</span></div>
                    </div>
                </div>
            </section>

            <section class="transaction-section">
                <div class="section-header">
                    <h2>Riwayat Transaksi Outlet Terbaru</h2>
                    <a href="#" class="view-all-link">Lihat Semua →</a>
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
                                <?php while($row_tx = $result_transactions->fetch_assoc()): 
                                    // Pemetaan kelas CSS berdasarkan nilai status di database Anda
                                    $badge_class = 'status-pending';
                                    if(strtolower($row_tx['status']) === 'success' || strtolower($row_tx['status']) === 'selesai') $badge_class = 'status-success';
                                    if(strtolower($row_tx['status']) === 'cancel' || strtolower($row_tx['status']) === 'dibatalkan') $badge_class = 'status-danger';
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
                                <tr><td colspan="5" style="text-align:center; color:var(--text-muted);">Belum ada data transaksi hari ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="products-section">
                <div class="section-header"><h2>Daftar Produk Kantin</h2></div>
                <div class="products-grid">
                    <?php if ($result_menu->num_rows > 0): ?>
                        <?php while($row_menu = $result_menu->fetch_assoc()): 
                            $is_habis = (strtolower($row_menu['STOK']) === 'habis' || $row_menu['STOK'] == 0);
                            $foto = !empty($row_menu['FOTO_MENU']) ? $row_menu['FOTO_MENU'] : 'default.jpg';
                        ?>
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <img src="images/<?php echo htmlspecialchars($foto); ?>" onerror="this.src='https://via.placeholder.com/300x180?text=Menu+Kantin'" alt="Menu">
                                <?php if (!$is_habis): ?>
                                    <span class="badge badge-available">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge badge-empty">Habis</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($row_menu['NAMA_MENU']); ?></h3>
                                <p class="product-price">Rp <?php echo number_format($row_menu['HARGA'], 0, ',', '.'); ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color:var(--text-muted);">Belum ada menu yang didaftarkan pada kantin ini.</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <aside class="sidebar-content">
            
            <div class="status-control-card">
                <?php if ($status_kantin === '1'): ?>
                    <div class="status-indicator indicator-active">● Kantin Sedang Aktif</div>
                    <form action="update_status.php" method="POST">
                        <input type="hidden" name="id_kantin" value="<?php echo $id_kantin; ?>">
                        <input type="hidden" name="status" value="0">
                        <button type="submit" class="btn-toggle-status btn-deactivate">Nonaktifkan Kantin</button>
                    </form>
                <?php else: ?>
                    <div class="status-indicator indicator-inactive">● Kantin Nonaktif / Tutup</div>
                    <form action="update_status.php" method="POST">
                        <input type="hidden" name="id_kantin" value="<?php echo $id_kantin; ?>">
                        <input type="hidden" name="status" value="1">
                        <button type="submit" class="btn-toggle-status btn-activate">Aktifkan Kantin</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="sidebar-card">
                <h3>Sesi Penjual</h3>
                <p style="font-size:14px; margin-top:8px; font-weight:600; color:var(--text-main);">
                    👤 <?php echo htmlspecialchars($_SESSION['nama']); ?>
                </p>
                <p style="font-size:11px; color:var(--text-muted); font-weight:700; text-transform:uppercase;">
                    Role: <?php echo htmlspecialchars($_SESSION['role']); ?>
                </p>
            </div>
        </aside>

    </div>
</body>
</html>
<?php 
// Close resource database di akhir siklus eksekusi script
$stmt_m->close();
$conn->close();
?>