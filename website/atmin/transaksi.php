<?php
session_start();
require_once __DIR__ . "/../include/koneksi.php";

// Pastikan parameter ID kantin aman diambil dari URL
$id_kantin = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Perbaikan SQL: Ambil kolom tanggal sekalian untuk pengaman ORDER BY
$query_trx_list = "
SELECT t.kode_pesanan, t.tgl, t.waktu, u.NAMA_LENGKAP as siswa, t.total, t.status
FROM transaksi t
JOIN users u ON t.id_user = u.ID
WHERE t.id_kantin = ?
ORDER BY t.tgl DESC, t.waktu DESC
";

$stmt_tl = $conn->prepare($query_trx_list);
if (!$stmt_tl) die("Prepare transaksi gagal: " . $conn->error);

$stmt_tl->bind_param("i", $id_kantin);
$stmt_tl->execute();
$result_transactions = $stmt_tl->get_result();
$stmt_tl->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Outlet</title>
</head>
<body>
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
                            <th>TANGGAL</th> <th>WAKTU</th>
                            <th>SISWA</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_transactions->num_rows > 0): ?>
                            <?php while ($row_tx = $result_transactions->fetch_assoc()):
                                // Management Class Badge Status
                                $badge_class = 'status-pending';
                                $status_clean = strtolower($row_tx['status']);
                                
                                if ($status_clean === 'success' || $status_clean === 'selesai') {
                                    $badge_class = 'status-success';
                                } elseif ($status_clean === 'cancel' || $status_clean === 'dibatalkan') {
                                    $badge_class = 'status-danger';
                                }
                            ?>
                                <tr>
                                    <td class="tx-id">#<?php echo htmlspecialchars($row_tx['kode_pesanan']); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row_tx['tgl'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row_tx['waktu'])); ?></td>
                                    <td><?php echo htmlspecialchars($row_tx['siswa']); ?></td>
                                    <td>Rp <?php echo number_format($row_tx['total'], 0, ',', '.'); ?></td>
                                    <td><span class="status-badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($row_tx['status']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; color:var(--text-muted); padding: 20px;">Belum ada data transaksi pada kantin ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
</body>
</html>