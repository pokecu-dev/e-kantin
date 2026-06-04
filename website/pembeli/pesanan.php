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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        body {
            background: #f5f5f5;
            color: #333;
            font-family: 'Poppins', sans-serif;

        }

        .nav-links a {
            text-decoration: none;
            color: #888;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a.active {
            color: var(--primary);
            border-bottom: 2px solid #F47B20;
            padding-bottom: 5px;
        }

        .container {
            padding: 20px;
            width: 100%;
            max-width: 800px;
            margin: 10px auto 40px;
        }

        h1 {
            font-size: 1.1rem;
            color: #1A1A1A;
          margin-bottom: 20px;
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
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
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

        .form-rate-box {
            margin-top: 15px;
            padding: 15px;
            background: #fafafa;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
        }

        .form-rate-box h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }

        .rating-control {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .rating-control button {
            background: #fff;
            border: 1px solid #ddd;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .rating-control input {
            width: 45px;
            text-align: center;
            border: 1px solid #ddd;
            padding: 4px;
            border-radius: 6px;
            font-weight: 600;
        }

        .form-rate-box textarea {
            width: 100%;
            height: 55px;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 8px;
            font-family: inherit;
            font-size: 13px;
            resize: none;
            margin-bottom: 8px;
            outline: none;
        }

        .form-rate-box textarea:focus {
            border-color: #F47B20;
        }

        .btn-submit-rate {
            background: #F47B20;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .rating-box {
            margin-bottom: 12px;
        }

        .rating-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .stars {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 32px;
            cursor: pointer;
            color: #ccc;
            transition: 0.2s;
        }

        .star.active {
            color: #F47B20;
        }

        .star:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt="Logo"></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span><span></span><span></span>
            </label>
            <ul class="nav-links">
                <li><a href="pembeli.php">Beranda</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <li><a href="pesanan.php" class="active">Pesanan</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>
<!-- 
    <div class="back-header">
        <a href="pembeli.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>
    </div> -->
    <div class="container" >
        <h1 style="margin-top: 70px;">Riwayat Pesanan Kamu</h1>

        <div class="pesanan-list"></div>

        <div class="pesanan-list">
            <?php
            if ($result_pesanan && $result_pesanan->num_rows > 0) {
                while ($row = $result_pesanan->fetch_assoc()):


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
                            <p>Tanggal Pesan: <strong><?= date('d M Y, H:i', strtotime(($row['TGL'] ?? '') . ' ' . ($row['WAKTU'] ?? ''))); ?> WIB</strong></p>
                            <p>Total Pembayaran: <strong class="total-harga">Rp <?= number_format($row['TOTAL'] ?? $row['total'], 0, ',', '.'); ?></strong></p>
                        </div>

                        <?php if ($status_raw == 'selesai'): ?>
                            <div class="form-rate-box">
                                <?php
                                $rateedit = false;
                                $id_transaksi = $row['ID_TRANSAKSI'] ?? $row['id_transaksi'];
                                
                                // Cek apakah transaksi ini sudah pernah diberi ulasan
                                $sql_detail = "
                                SELECT 
                                    detail_transaksi.ID_MENU,
                                    tb_menu.ID_KANTIN
                                FROM detail_transaksi
                                JOIN tb_menu 
                                    ON detail_transaksi.ID_MENU = tb_menu.ID_MENU
                                WHERE detail_transaksi.ID_TRANSAKSI = '$id_transaksi'
                                LIMIT 1
                                ";
                                $query_detail = $conn->query($sql_detail);

                                if (!$query_detail) {
                                    die($conn->error);
                                }

                                $data_menu = $query_detail->fetch_assoc();

                                if ($data_menu):
                                    $id_menu = $data_menu['ID_MENU'];
                                    $id_kantin = $data_menu['ID_KANTIN'];
                                    $rateedit = false;

                                $sql_rate = "SELECT * FROM rating WHERE ID_USER = '$id_user' AND ID_MENU = '$id_menu' AND ID_KANTIN = '$id_kantin'";
                                    $query_rate = $conn->query($sql_rate);
                                    if ($query_rate && $query_rate->num_rows > 0) {
                                        $rateedit = true;
                                    }

                                    if (!$rateedit):
                                ?>
                                        <h3>Beri Rating!</h3>
                                        <form action="pro_tesrate.php" method="post">
                                            <input type="hidden" name="id_menu" value="<?= $id_menu ?>">
                                            <input type="hidden" name="id_user" value="<?= $id_user ?>">
                                            <input type="hidden" name="id_kantin" value="<?= $id_kantin ?>">
                                            
                                            <div class="rating-box">
                                                <label class="rating-label">Rating:</label>

                                                <div class="stars">
                                                    <span class="star" data-value="1">★</span>
                                                    <span class="star" data-value="2">★</span>
                                                    <span class="star" data-value="3">★</span>
                                                    <span class="star" data-value="4">★</span>
                                                    <span class="star" data-value="5">★</span>
                                                </div>

                                                <input type="hidden" name="rating" class="rating-value" value="0">
                                            </div>
    
                                        <textarea name="desk" placeholder="Komentar" required></textarea>
                                            <button type="submit" name="submit" class="btn-submit-rate">Kirim</button>
                                        </form>
                                    <?php else: ?>
                                        <p style="font-size: 13px; color: #2ecc71; font-weight: 600;">✓ Kamu sudah memberikan ulasan untuk menu ini</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                                        
                        <div class="card-footer">
                            <a href="struckdigital.php?trx=<?= $row['ID_TRANSAKSI'] ?? $row['id_transaksi']; ?>" class="btn-detail">Lihat Detail Struk</a>
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

    <script>
    document.querySelectorAll('.rating-box').forEach(box => {

        const stars = box.querySelectorAll('.star');
        const input = box.querySelector('.rating-value');

        stars.forEach((star, index) => {

            star.addEventListener('click', () => {

                let rating = index + 1;
                input.value = rating;

                stars.forEach((s, i) => {

                    if (i < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }

                });

            });

        });

    });
    </script>
    
</body>

</html>