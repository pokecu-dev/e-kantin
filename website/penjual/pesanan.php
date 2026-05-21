<?php

require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/penjualC.php";

$id_user_login = $_SESSION['id_user'] ?? 24;

// Cari ID Kantin yang dimiliki oleh penjual ini dari tabel list_kantin
$sql_kantin = "SELECT ID FROM list_kantin WHERE id_penjual = '$id_user_login' LIMIT 1";
$query_kantin = $conn->query($sql_kantin);
$data_kantin = $query_kantin->fetch_assoc();

$id_kantin_toko = $data_kantin['ID'] ?? 1;


// 2. QUERY RIWAYAT TRANSAKSI (Sesuai kolom tabel detail_transaksi kamu)
// Kita gunakan kolom `nama_menu`, `qty`, dan `subtotal` langsung dari detail_transaksi
$sql_transaksi = "SELECT t.id AS id_transaksi, dt.nama_menu, dt.qty, dt.subtotal, t.waktu 
                  FROM transaksi t
                  JOIN detail_transaksi dt ON t.id = dt.id_transaksi
                  WHERE t.id_kantin = '$id_kantin_toko'
                  ORDER BY t.waktu DESC 
                  LIMIT 5";

$query_transaksi = $conn->query($sql_transaksi);


// 3. QUERY MENGHITUNG PESANAN MASUK HARI INI
$sql_count = "SELECT COUNT(*) as total_order 
              FROM transaksi 
              WHERE id_kantin = '$id_kantin_toko' AND DATE(tgl) = CURDATE()";

$query_count = $conn->query($sql_count);
$res_count = $query_count->fetch_assoc();
$pesanan_masuk_hari_ini = $res_count['total_order'] ?? 0;


?>


<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Kantin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
    
        .container {
            margin: 90px auto 30px;
            padding: 0 20px;
            max-width: 1300px;
        }

        .parent {
            background: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
        }

        /* HEADER + ROW */
        .header-tabel,
        .produk {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            gap: 20px;
            padding: 16px 18px;
            min-width: 750px;
        }

        /* HEADER */
        .header-tabel {
            background: #fff5eb;
            border-radius: 14px;
            font-weight: 600;
            color: #492509;
            margin-bottom: 10px;
        }

        /* CARD */
        .card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #eee;
        }

        /* ROW */
        .produk {
            border-bottom: 1px solid #f1f1f1;
        }

        .produk:last-child {
            border-bottom: none;
        }

        /* PRODUK KIRI */
        .div1 {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* FOTO */
        .detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .detail img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
        }

        /* ANTREAN */
        .antri {
            font-size: 11px;
            background: #fff5eb;
            color: #F47B20;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* NAMA */
        .div1 p {
            font-weight: 600;
            color: #1e293b;
        }

        .div1 small {
            color: #64748b;
        }

        /* BUTTON */
        .btn {
            border: none;
            outline: none;
            padding: 12px 18px;
            border-radius: 10px;
            background: #F47B20;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s;
            width: 100%;
        }

        .btn:hover {
            background: #d86412;
            transform: translateY(-2px);
        }

        /* MOBILE */
        @media (max-width: 768px) {

            .header-tabel,
            .produk {
                min-width: 650px;
            }

            .container {
                padding: 0 12px;
            }

            .btn {
                font-size: 13px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>

            <!-- Burger Menu (Mobile Only) -->
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <ul class="nav-links">
                <li><a href="penjual.php">Beranda</a></li>
                <li><a href="pesanan.php" class="active">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>
    <!------------------------- PESANAN -------------------------->

    <div class="container">
        <h2 class="text">Daftar Pesanan</h2>
        <div class="parent">
            <div class="header-tabel">
                <div>Produk</div>
                <div>Payment</div>
                <div>Total</div>
                <div>Status</div>
            </div>
            <div class="card">
                <div class="produk">
                    <div class="div1">
                        <div class="detail">
                            <small class="antri">Antrean: 10</small>
                            <img src="nasi-goreng.jpg">
                        </div>
                        <div>
                            <p>Nasi Goreng</p>
                            <small>Varian: Spesial</small>
                        </div>
                    </div>

                    <div>CASH</div>

                    <div>8.000</div>

                    <div>
                        <button class="btn">Terima Pesanan</button>
                    </div>
                </div>

                <div class="produk">
                    <div class="div1">
                        <div class="detail">
                            <small class="antri">Antrean: 10</small>
                            <img src="nasi-goreng.jpg">
                        </div>

                        <div>

                            <p>Nasi Goreng</p>
                            <!-- <small>Varian: Spesial</small> -->
                            <small style="color: #F47B20;">Detail</small>
                        </div>
                    </div>

                    <div>CASH</div>

                    <div>8.000</div>

                    <div>
                        <button class="btn">Terima Pesanan</button>
                    </div>
                </div>
                
            </div>
            <div class="header-tabel">
                <div>ID Transaksi</div>
                <div>Menu</div>
                <div>Jumlah</div>
                <div>Total Harga</div>
                <div>Waktu</div>
                <div>Aksi</div>
            </div>

                <?php if ($query_transaksi && $query_transaksi->num_rows > 0): ?>
                        <?php while ($row = $query_transaksi->fetch_assoc()): ?>
                            <div class="grid-row-data">
                                <div>#-<?php echo $row['id_transaksi']; ?></div>
                                <strong><?php echo htmlspecialchars($row['nama_menu']); ?></strong>
                                <div><?php echo $row['qty']; ?> Porsi</div>
                                <div>Rp <?php echo number_format($row['subtotal'], 0, ',', '.'); ?></div>
                                <div><?php echo date('H:i', strtotime($row['waktu'])); ?> WIB</div>

                                <div>
                                    <button type="button" class="btn-detail btn-buka-modal" data-id="<?php echo $row['id_transaksi']; ?>">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="grid-row-data" style="grid-template-columns: 1fr; text-align: center; color: #888;">
                            Belum ada riwayat transaksi untuk kantin ini.
                        </div>
                    <?php endif; ?>
        </div>
    </div>
    <br>
    <br>


</body>

</html>