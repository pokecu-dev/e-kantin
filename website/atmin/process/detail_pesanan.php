<?php
require_once __DIR__ . "/../../include/koneksi.php";

// Menangkap Kode Pesanan string (contoh: ORD-20260520-9F36A) dari JavaScript
$kode_pesanan_get = $_GET['id'] ?? null;

if (!$kode_pesanan_get) {
    die("<p style='color:red; text-align:center; font-family:sans-serif;'>Kode Pesanan Kosong!</p>");
}

// =========================================================================
// 1. QUERY UTAMA: Mengambil data induk berdasarkan KODE_PESANAN (Huruf Besar)
// =========================================================================
$sql_induk = "SELECT t.*, u.NAMA_LENGKAP 
              FROM transaksi t
              LEFT JOIN users u ON t.ID_USER = u.ID
              WHERE t.KODE_PESANAN = ? LIMIT 1";

$stmt = $conn->prepare($sql_induk);
if (!$stmt) {
    die("Prepare data induk gagal: " . $conn->error);
}
$stmt->bind_param("s", $kode_pesanan_get);
$stmt->execute();
$transaksi_utama = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$transaksi_utama) {
    die("<p style='color:red; text-align:center; font-family:sans-serif;'>Data transaksi tidak ditemukan di database!</p>");
}

// Ambil ID_TRANSAKSI berupa angka untuk query detail item di bawah
$id_transaksi_angka = $transaksi_utama['ID_TRANSAKSI'];

// Tentukan Warna Badge Status
$status_clean = strtolower($transaksi_utama['STATUS']);
$status_color = "#D97706"; // Oranye (diproses / pending)
if ($status_clean === 'success' || $status_clean === 'selesai' || $status_clean === 'dikonfirmasi' || $status_clean === 'siap diambil') {
    $status_color = "#22C55E"; // Hijau
} elseif ($status_clean === 'cancel' || $status_clean === 'dibatalkan' || $status_clean === 'ditolak') {
    $status_color = "#EF4444"; // Merah
}

// =========================================================================
// 2. QUERY ITEM (ANAK): Mencari menggunakan ID_TRANSAKSI (Angka) hasil Query 1
// =========================================================================
$sql_item = "SELECT * FROM detail_transaksi WHERE ID_TRANSAKSI = ?";
$stmt_item = $conn->prepare($sql_item);
if (!$stmt_item) {
    die("Prepare data item gagal: " . $conn->error);
}
$stmt_item->bind_param("i", $id_transaksi_angka);
$stmt_item->execute();
$query_item = $stmt_item->get_result();
?>

<style>
    .info-section { display: flex; flex-direction: column; gap: 10px; font-size: 13px; margin-bottom: 24px; text-align: left; }
    .info-row { display: flex; justify-content: space-between; border-bottom: 1px dashed #F1F5F9; padding-bottom: 8px; }
    .info-label { color: #888; }
    .info-value { font-weight: 500; color: #333; }
    
    .item-list { display: flex; flex-direction: column; gap: 14px; margin-top: 15px; width: 100%; }
    .item-grid { 
        display: grid; 
        grid-template-columns: 1fr 60px 100px; 
        align-items: center; 
        font-size: 13px; 
        gap: 10px; 
        width: 100%;
    }
    
    .item-header { 
        font-weight: 600; 
        color: #94A3B8; 
        font-size: 11px; 
        text-transform: uppercase; 
        border-bottom: 2px solid #E2E8F0; 
        padding-bottom: 6px; 
    }
    .item-nama { font-weight: 600; color: #1E293B; text-align: left; }
    .item-qty { text-align: center; color: #64748B; font-weight: 500; }
    .item-subtotal { text-align: right; font-weight: 600; color: #1E293B; }
    
    .total-section { margin-top: 25px; border-top: 2px dashed #E2E8F0; padding-top: 15px; display: flex; justify-content: space-between; align-items: center; }
    .total-label { font-size: 14px; font-weight: 600; color: #1E293B; }
    .total-harga { font-size: 18px; font-weight: 700; color: #E06313; }
</style>

<div class="info-section">
    <div class="info-row">
        <span class="info-label">Kode Pesanan</span>
        <span class="info-value" style="color:#E06313; font-weight:700;">#<?php echo htmlspecialchars($transaksi_utama['KODE_PESANAN']); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Tanggal / Waktu</span>
        <span class="info-value">
            <?php echo date('d M Y', strtotime($transaksi_utama['TGL'])); ?> | <?php echo date('H:i', strtotime($transaksi_utama['WAKTU'])); ?> WIB
        </span>
    </div>
    <div class="info-row">
        <span class="info-label">Nama Pembeli</span>
        <span class="info-value"><?php echo htmlspecialchars($transaksi_utama['NAMA_LENGKAP'] ?? 'Umum / Guest'); ?></span>
    </div>
    <div class="info-row">
        <span class="info-label">Status Pesanan</span>
        <span class="info-value" style="text-transform: uppercase; font-size:12px; font-weight:700; color:<?php echo $status_color; ?>;">
            <?php echo htmlspecialchars($transaksi_utama['STATUS']); ?>
        </span>
    </div>
</div>

<div class="item-list">
    <div class="item-grid item-header">
        <div>Nama Menu</div>
        <div style="text-align: center;">Jumlah</div>
        <div style="text-align: right;">Subtotal</div>
    </div>

    <?php 
    $grand_total = 0;
    while($item = $query_item->fetch_assoc()): 
        // Menggunakan huruf besar sesuai dengan isi tabel detail_transaksi kamu
        $nama_menu = $item['NAMA_MENU'];
        $harga_menu = $item['HARGA'];
        $qty_menu = $item['QTY'];
        $subtotal_menu = $item['SUBTOTAL'];
        
        $grand_total += $subtotal_menu;
    ?>
        <div class="item-grid">
            <div class="item-nama">
                <?php echo htmlspecialchars($nama_menu); ?>
                <div style="font-size: 11px; color: #94A3B8; font-weight: 400; margin-top: 2px;">
                    @Rp <?php echo number_format($harga_menu, 0, ',', '.'); ?>
                </div>
            </div>
            <div class="item-qty"><?php echo $qty_menu; ?>x</div>
            <div class="item-subtotal">Rp <?php echo number_format($subtotal_menu, 0, ',', '.'); ?></div>
        </div>
    <?php endwhile; ?>
</div>

<div class="total-section">
    <span class="total-label">Total Pembayaran</span>
    <span class="total-harga">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
</div>

<?php 
$stmt_item->close();
$conn->close(); 
?>