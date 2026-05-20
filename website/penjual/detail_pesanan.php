<?php
// 1. Hubungkan ke database dan aktifkan session
require_once __DIR__ . "/../include/koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek koneksi ke database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Ambil ID Transaksi dari URL (Metode GET)
$id_transaksi = $_GET['id'] ?? null;

if (!$id_transaksi) {
    die("ID Transaksi tidak ditemukan atau tidak valid!");
}

// 3. Query Utama (Induk): Menggabungkan transaksi dengan NAMA_LENGKAP dari tabel users
$sql_induk = "SELECT t.*, u.NAMA_LENGKAP 
              FROM transaksi t
              LEFT JOIN users u ON t.id_user = u.ID
              WHERE t.id = '$id_transaksi' LIMIT 1";

$query_induk = $conn->query($sql_induk);
$transaksi_utama = $query_induk->fetch_assoc();

if (!$transaksi_utama) {
    die("Data transaksi tidak ditemukan di database!");
}

// 4. Query Item (Anak): Mengambil item makanan dari tabel detail_transaksi
$sql_item = "SELECT * FROM detail_transaksi WHERE id_transaksi = '$id_transaksi'";
$query_item = $conn->query($sql_item);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f5f5f5; color: #333; padding: 24px; }
        .container { max-width: 600px; margin: 0 auto; } */
        
        .btn-kembali {
            display: inline-block;
            margin-bottom: 20px;
            color: #e06313;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        /* Desain Nota Struk */
        .nota-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #eaeaea;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .nota-header {
            text-align: center;
            border-bottom: 2px dashed #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
.nota-header img { 
    max-width: 100%;    
    height: auto;      
    max-height: 60px;   
    object-fit: contain;
}
        .nota-header p { font-size: 13px; color: #666; margin-top: 4px; }

        /* Info Metadata Transaksi */
        .info-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 13px;
            color: #555;
            margin-bottom: 24px;
        }
        .info-row { display: flex; justify-content: space-between; }
        .info-label { color: #888; }
        .info-value { font-weight: 500; color: #333; }

        /* Rincian Menu (CSS Grid) */
        .item-list { display: flex; flex-direction: column; gap: 16px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        .item-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            font-size: 14px;
            align-items: center;
        }
        .item-header { font-weight: 500; color: #888; font-size: 12px; text-transform: uppercase; margin-bottom: -4px; }
        .item-nama { font-weight: 600; color: #111; }
        .item-qty { text-align: center; color: #555; }
        .item-subtotal { text-align: right; font-weight: 500; color: #333; }

        /* Total Pembayaran */
        .total-section {
            margin-top: 24px;
            border-top: 2px dashed #e2e8f0;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label { font-size: 16px; font-weight: 600; color: #111; }
        .total-harga { font-size: 20px; font-weight: 700; color: #e06313; }
    </style>


</head>
<body>
<div class="container">
    <p href="index.php" class="btn-kembali">Detail </p>

    <div class="nota-card">
        <div class="nota-header">
            <h2><img src="../../source/icon/logo1.svg" alt=""></h2>
            <p>Rincian Transaksi Pembelian</p>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">ID Transaksi</span>
                <span class="info-value">#-<?php echo $transaksi_utama['id']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal / Waktu</span>
                <span class="info-value"><?php echo date('d M Y', strtotime($transaksi_utama['tgl'])); ?> | <?php echo date('H:i', strtotime($transaksi_utama['waktu'])); ?> WIB</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Pembeli</span>
                <span class="info-value"><?php echo htmlspecialchars($transaksi_utama['NAMA_LENGKAP'] ?? 'Umum / Guest'); ?></span>
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
                $grand_total += $item['subtotal']; // Total penjumlahan subtotal otomatis
            ?>
                <div class="item-grid">
                    <div class="item-nama">
                        <?php echo htmlspecialchars($item['nama_menu']); ?>
                        <div style="font-size: 11px; color: #888; font-weight: 400;">
                            @Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="item-qty"><?php echo $item['qty']; ?>x</div>
                    <div class="item-subtotal">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="total-section">
            <span class="total-label">Total Pembayaran</span>
            <span class="total-harga">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
        </div>
    </div>
</div>

    
</body>
</html>