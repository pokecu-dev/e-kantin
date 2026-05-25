<?php
require_once '../include/koneksi.php';
require_once __DIR__ . "/../include/session/pembeliC.php";

$id_user = $_SESSION['id_user'] ?? $_SESSION['ID_USER'] ?? 0;

$sql = "SELECT * FROM transaksi WHERE ID_USER = ? ORDER BY TGL DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result_pesanan = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - KantinKita</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            padding: 20px;
            width: 100%;
            max-width: 800px;
            margin: 10px auto 40px;
        }

        h1 {
            font-size: 1.6rem;
            color: #1A1A1A;
            margin: 0;
        }

        .back-header {
            display: flex;
            align-items: center; 
            gap: 16px;          
            margin: 120px auto 0; 
            width: 95%;
        }

        .back {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 0;
            max-width: 1100px;
            margin: 100px auto 0;
            width: 95%;
        }

        .btn-back img {
            width: 24px;
            height: 24px;
            display: block;
        }

        .back h2 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .pesanan-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .card-pesanan {
            background: #ffffff;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 1px solid #eee;
            transition: transform 0.2s ease;
        }

        .card-pesanan:hover {
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #eee;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .nota {
            font-weight: 700;
            color: #1A1A1A;
            font-size: 15px;
        }

        .status {
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            text-transform: capitalize;
        }

        /*warna status pesanan */
        .status.proses {
            background: #fff5eb;
            color: #F47B20;
        }

        .status.selesai {
            background: #e6f7ed;
            color: #2ecc71;
        }

        .status.batal {
            background: #fce8e6;
            color: #e74c3c;
        }

        .card-body p {
            margin: 6px 0;
            font-size: 14px;
            color: #666;
        }

        .card-body strong {
            color: #1A1A1A;
        }

        .total-harga {
            font-size: 16px;
            color: #F47B20 !important;
            font-weight: 700;
        }

        .card-footer {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #f5f5f5;
            display: flex;
            justify-content: flex-end;
        }

        .btn-detail {
            text-decoration: none;
            background: transparent;
            color: #F47B20;
            border: 1px solid #F47B20;
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-detail:hover {
            background: #F47B20;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt="Logo"></div>
            <ul class="nav-links">
                <li><a href="pembeli.php">Beranda</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <li><a href="pesanan.php" class="active">Pesanan</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="back-header">
        <a href="pembeli.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>
    </div>
     <div class="container">
        <h1>Riwayat Pesanan Kamu</h1>

        <div class="pesanan-list"></div>

        <div class="pesanan-list">
            <?php 
            if ($result_pesanan && $result_pesanan->num_rows > 0) {
                while($row = $result_pesanan->fetch_assoc()):
                    
                 
                    $status_raw = strtolower($row['STATUS'] ?? 'proses');
                    $status_class = 'proses';
                    if ($status_raw == 'selesai') $status_class = 'selesai';
                    if ($status_raw == 'batal' || $status_raw == 'dibatalkan') $status_class = 'batal';
            ?>
                <div class="card-pesanan">
                    <div class="card-header">
                        <span class="nota">ID Transaksi: #<?= $row['ID_TRANSAKSI'] ?? $row['id_transaksi']; ?></span>
                        <span class="status <?= $status_class; ?>"><?= $row['STATUS'] ?? 'Diproses'; ?></span>
                    </div>
                    
                    <div class="card-body">
                        <p>Tanggal Pesan: <strong><?= date('d M Y, H:i', strtotime($row['TGL'] ?? $row['tanggal_transaksi'])); ?> WIB</strong></p>
                        <p>Total Pembayaran: <strong class="total-harga">Rp <?= number_format($row['TOTAL'] ?? $row['total'], 0, ',', '.'); ?></strong></p>
                    </div>

                    <div class="card-footer">
                        <a href="struckdigital.php?id=<?= $row['ID_TRANSAKSI'] ?? $row['id_transaksi']; ?>" class="btn-detail">Lihat Detail Struk</a>
                    </div>
                </div>
            <?php 
                endwhile; 
            } else {
                echo "
                <div style='text-align: center; margin-top: 50px; color: #888;'>
                    <p style='font-size: 18px; font-weight:600;'>Belum ada riwayat pesanan</p>
                    <p style='font-size: 14px;'>Yuk, cari jajanan enak dulu di beranda!</p>
                </div>";
            }
            $stmt->close();
            ?>
        </div>
    </div>

</body>
</html>