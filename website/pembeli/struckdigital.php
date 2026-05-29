<?php

require_once __DIR__ . '/../include/koneksi.php';
require_once __DIR__ . "./../include/session/pembeliC.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = (int)$_SESSION['id_user'];
$trxId   = isset($_GET['trx']) ? (int)$_GET['trx'] : 0;

$transaction = null;
$kantinName  = '';
$trxIdStr    = '';
$tglTrx      = '';
$waktuTrx    = '';
$items       = [];
$subtotal    = 0;

if ($trxId > 0) {
    $qTrx = mysqli_query($conn, "
        SELECT t.*, k.NAMA_KANTIN
        FROM transaksi t
        JOIN list_kantin k ON t.id_kantin = k.ID
        WHERE t.ID_TRANSAKSI = $trxId AND t.id_user = $id_user
        LIMIT 1
    ");

    if ($transaction = mysqli_fetch_assoc($qTrx)) {
        $kantinName = $transaction['NAMA_KANTIN'];
        $tglTrx     = $transaction['TGL'];
        $waktuTrx   = $transaction['WAKTU'];
        $trxIdStr   = $transaction['KODE_PESANAN'] ?? 'TRX-' . $trxId;
        $metode = $transaction['METODE'];
        $id_kantin = $transaction['ID_KANTIN'];

        $qItems = mysqli_query($conn, "
            SELECT nama_menu, harga, qty, subtotal
            FROM detail_transaksi
            WHERE id_transaksi = $trxId
        ");

        while ($row = mysqli_fetch_assoc($qItems)) {
            $row['subtotal'] = (int)$row['subtotal'];
            $subtotal       += $row['subtotal'];
            $items[]         = $row;
        }
    }
}

$grandTotal = $subtotal;
$f = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Digital - E-Kantin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
       .st-wrap {
    max-width: 480px;
    margin: 20px auto 120px;
    padding: 0 16px;
}

.st-page-header {
    display: flex;
    align-items: center;
    justify-content: flex-start; 
    gap: 16px;
    padding: 14px 0 10px;
    margin-bottom: 16px;
    width: 100%;
}

.st-page-header .btn-back {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.st-page-header .btn-back img {
    width: 24px;
    height: 24px;
    display: block;
}

.st-sukses-text {
    flex: 1;
}

.st-sukses-text h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1A1A1A;
    line-height: 1.2;
}

.st-sukses-text p {
    margin: 2px 0 0 0;
    font-size: 12px;
    color: #64748b;
    line-height: 1.2;
}

.st-btn-print {
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    color: #F47B20;
    font-weight: 600;
    flex-shrink: 0;
}

.st-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.09);
    margin-bottom: 16px;
}

.st-card-header {
    background: #F47B20;
    color: #fff;
    padding: 24px 24px 20px;
    text-align: center;
}

.st-card-header .kantin-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    opacity: 0.85;
    margin: 0 0 4px;
}

.st-card-header h3 {
    margin: 0 0 16px;
    font-size: 20px;
    font-weight: 800;
}

.st-meta {
    display: flex;
    justify-content: center;
    gap: 32px;
}

.st-meta-item {
    text-align: center;
}

.st-meta-item .label {
    font-size: 10px;
    opacity: 0.75;
    display: block;
    margin-bottom: 2px;
}

.st-meta-item .value {
    font-size: 13px;
    font-weight: 700;
}

.st-status {
    background: #fff8f2;
    border-bottom: 1px solid #ffe0c0;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #e65c00;
}

.st-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #F47B20;
    flex-shrink: 0;
}

.st-body {
    padding: 20px 20px 0;
}

.st-section-title {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 0 0 12px;
}

.st-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 11px 0;
    border-bottom: 1px solid #f5f5f5;
}

.st-item:last-child {
    border-bottom: none;
}

.st-item-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.st-item-qty {
    font-size: 13px;
    font-weight: 700;
    color: #F47B20;
    min-width: 28px;
    flex-shrink: 0;
}

.st-item-nama {
    font-size: 14px;
    color: #1A1A1A;
    font-weight: 500;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.st-item-harga {
    font-size: 14px;
    font-weight: 600;
    color: #1A1A1A;
    flex-shrink: 0;
    margin-left: 8px;
}

.st-dashed {
    border: none;
    border-top: 2px dashed #e8e8e8;
    margin: 16px 0;
}

.st-summary {
    padding: 0 20px;
}

.st-summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #64748b;
    padding: 5px 0;
}

.st-total-box {
    background: #fff8f2;
    border-radius: 14px;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 12px 20px 0;
}

.st-total-box .label {
    font-size: 15px;
    font-weight: 700;
    color: #1A1A1A;
}

.st-total-box .nilai {
    font-size: 22px;
    font-weight: 800;
    color: #F47B20;
}

.st-footer {
    text-align: center;
    padding: 18px 20px 22px;
    font-size: 12px;
    color: #aaa;
    line-height: 1.8;
}

.st-actions {
    display: flex;
    gap: 12px;
    margin-top: 4px;
}

.st-btn-outline {
    flex: 1;
    height: 48px;
    border: 2px solid #F47B20;
    border-radius: 14px;
    background: #fff;
    color: #F47B20;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.st-btn-outline:hover {
    background: #fff5ee;
}

.st-btn-solid {
    flex: 1;
    height: 48px;
    border: none;
    border-radius: 14px;
    background: #F47B20;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s;
}

.st-btn-solid:hover {
    opacity: 0.9;
}

.st-error {
    text-align: center;
    background: #fff;
    border-radius: 20px;
    padding: 40px 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.09);
}

.st-error .icon {
    font-size: 56px;
    margin-bottom: 12px;
}

.st-error h3 {
    margin: 0 0 8px;
    font-size: 17px;
    font-weight: 700;
    color: #1A1A1A;
}

.st-error p {
    margin: 0 0 20px;
    font-size: 13px;
    color: #64748b;
}

.st-error a {
    display: inline-block;
    padding: 12px 28px;
    border-radius: 14px;
    background: #F47B20;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
}

@media print {
    .no-print {
        display: none !important;
    }

    body {
        background: white;
    }

    .st-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}

@media (max-width: 480px) {
    .st-wrap {
        margin-top: 30px; 
        margin-bottom: 60px;
    }

    .st-sukses-text h2 {
        font-size: 15px;
    }

    .st-sukses-text p {
        font-size: 11px;
    }
}
    </style>
</head>

<body>



    <div class="st-wrap">

        <!-- Page Header -->
        <div class="st-wrap">

            <div class="st-page-header no-print">
                <a href="pembeli.php" class="btn-back">
                    <img src="../../source/icon/kembali.svg" alt="Kembali">
                </a>

                <?php if ($transaction): ?>
                    <div class="st-sukses-text">
                        <h2>Pesanan Berhasil! 🎉</h2>
                        <p>Tunjukkan struk ini kepada penjual</p>
                    </div>

            </div>
            <!-- Kartu Struk -->
            <div class="st-card">

                <!-- Header Oranye -->
                <div class="st-card-header">
                    <p class="kantin-label">E-Kantin SMKN 1 Boyolangu</p>
                    <h3><?= htmlspecialchars($kantinName) ?></h3>
                    <div class="st-meta">
                        <div class="st-meta-item">
                            <span class="label">Kode Pesanan</span>
                            <span class="value"><?= htmlspecialchars($trxIdStr) ?></span>
                        </div>
                        <div class="st-meta-item">
                            <span class="label">Waktu</span>
                            <span class="value"><?= date('d/m/Y', strtotime($tglTrx)) ?> <?= substr($waktuTrx, 0, 5) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="st-status">
                    <div class="st-status-dot"></div>
                    Menunggu Konfirmasi Penjual
                </div>

                <!-- Body -->
                <div class="st-body">
                    <p class="st-section-title">Detail Pesanan</p>

                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $it): ?>
                            <div class="st-item">
                                <div class="st-item-left">
                                    <span class="st-item-qty"><?= (int)$it['qty'] ?>x</span>
                                    <span class="st-item-nama"><?= htmlspecialchars($it['nama_menu']) ?></span>
                                </div>
                                <span class="st-item-harga"><?= $f($it['subtotal']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align:center; color:#aaa; font-size:13px; padding: 16px 0;">
                            Tidak ada data item.
                        </p>
                    <?php endif; ?>
                </div>

                <hr class="st-dashed">

                <!-- Summary -->
                <div class="st-summary">
                    <div class="st-summary-row">
                        <span>Subtotal</span>
                        <span><?= $f($subtotal) ?></span>
                    </div>
                    <div class="st-summary-row">
                        <span>Biaya Layanan</span>
                        <span>Rp 0</span>
                    </div>
                </div>

                <!-- Total Box -->
                <div class="st-total-box">
                    <span class="label">Total Bayar</span>
                    <span class="nilai"><?= $f($grandTotal) ?></span>
                </div>

                <!-- Footer Struk -->
                <div class="st-footer">
                    <p>Terima kasih sudah memesan! 🙏</p>
                    <p>Harap tunjukkan struk ini kepada penjual</p>
                </div>

            </div>

            <!-- Tombol Aksi -->
            <div class="st-actions no-print">

                <button class="st-btn-solid" onclick="window.print()">🖨 Cetak / PDF</button>

                <?php
                    if ($metode === "qris") {
                        echo "<a class='st-btn-outline' href='qris.php?trx=$trxId&id_kantin=$id_kantin'>QRIS</a>";
                    }
                ?>
                <!-- qris.php?trx=$id_transaksi&id_kantin=$id_kantin -->


            </div>

        <?php else: ?>

            <!-- Error State -->
            <div class="st-error">
                <div class="icon">❌</div>
                <h3>Transaksi Tidak Ditemukan</h3>
                <p>Kode transaksi tidak valid atau bukan milik kamu.</p>
                <a href="pembeli.php">Kembali ke Beranda</a>
            </div>

        <?php endif; ?>

        </div>

</body>

</html>