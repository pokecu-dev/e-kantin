<?php
session_start();
require_once '../include/koneksi.php';

if (!isset($_GET['id'])) {
    echo "Menu tidak ditemukan!";
    exit;
}

$iduser = $_SESSION['id_user'];

$id = (int)$_GET['id'];

// Perbaikan: Di database rata-rata nama kolomnya ID_MENU (huruf besar)
$query = mysqli_query($conn, "SELECT * FROM tb_menu WHERE ID_MENU='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ada!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu - <?php echo $data['NAMA_MENU']; ?></title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
            width: 95%;
            max-width: 1100px; 
            background: #ffffff;  
            border-radius: 24px;
            margin: 120px auto 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: flex;
            gap: 50px;
            align-items: flex-start;
        }

        .back {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 0;
            max-width: 1100px;
            margin: 100px auto -100px;
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

        .gambar {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .gambar img {
            width: 100%;
            max-width: 450px;
            height: 400px;
            display: block;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .info {
            flex: 1;
        }

        .info h2 {
            font-size: 36px;
            font-weight: 700;
            text-align: left;
            margin-top: 0;
            margin-bottom: 10px;
            color: #111;
        }

        .row-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .stock {
            font-size: 15px;
            color: #666;
            background: #fdf2e9;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .rating {
            color: #F47B20;
            font-size: 20px;
            font-weight: 600;
        }

        .description-box {
            margin-top: 20px;
            background: #ffffff;
            padding: 20px;
            border-radius: 16px;
            font-size: 14px;
            color: #444;
            border: 1px solid #fedec6;
        }

        .description-box h3 {
            margin-top: 0;
            color: #222;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .description-box p {
            line-height: 1.8;
            margin: 0;
            color: #333;
        }

        .row-harga-qty {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 35px;
            padding-top: 15px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #ddd;
        }

        .harga {
            font-size: 32px;
            font-weight: 700;
            color: #F47B20;
        }

        .jumlah {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f5f5f5;
            padding: 4px;
            border-radius: 12px;
        }

        .jumlah button {
            width: 36px;
            height: 36px;
            border: none;
            background: #F47B20;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
        }

        .jumlah button:hover {
            background: #d66413;
        }

        .jumlah input {
            width: 45px;
            height: 36px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            border: none;
            background: transparent;
            outline: none;
        }

        .jumlah input::-webkit-outer-spin-button,
        .jumlah input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .footer-keranjang {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 25px;
            gap: 15px;
        }

        .icon-badge,
        #btnCheckout {
            flex: 1;
            padding: 15px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            border: 2px solid #F47B20;
            color: #F47B20;
        }

        .icon-badge {
            background: white;
            border: 2px solid #F47B20;
            color: #F47B20;
        }

        .icon-badge:hover {
            background: #fff5eb;
            transform: scale(0.97);
        }

        #btnCheckout {
            background: #F47B20;
            color: white; 
        }

        #btnCheckout:hover {
            background: #F47B20;
            transform: scale(0.97);
        }

        #btnCheckout:active {
            background: #F47B20 !important;
            color: white !important;
            border-color: #F47B20 !important;
            transform: scale(0.97);
        }

        @media(max-width: 768px) {
            .back {
                margin: 20px auto -10px;
            }

            .container {
                flex-direction: column;
                gap: 20px;
                padding: 20px;
                margin: 20px auto 100px;
            }

            .gambar, .info {
                width: 100%;
            }

            .gambar img {
                height: 250px;
                max-width: none;
                border-radius: 16px;
                width: 100%;

                object-fit: contain;     
                background-color: #fff; 
                padding: 10px;          
                border: 1px solid #eee;
            }

            .info h2 {
                font-size: 28px;
                margin-top: 10px;
            }

            .row-harga-qty {
                margin-top: 25px;
            }

            .harga {
                font-size: 26px;
            }

            .footer-keranjang {
                flex-direction: column;
                gap: 12px;
            }

            .icon-badge, #btnCheckout {
                width: 100%;
                height: 50px;
                border-radius: 16px;
            }
        }
    </style>
</head> 
<body>  

<div class="logo-mobile">
    <img src="../../source/icon/logo1.svg" alt="KantinKita">
</div>

<div class="logo-desktop">
    <img src="../../source/icon/logo1.svg" alt="KantinKita">
</div>

<div class="top-nav">
    <nav class="menu">
        <a href="pembeli.php">
            <img src="../../source/icon/home2.svg" alt="home"> <span class="nav-teks">Beranda</span>
        </a>
        <a href="keranjang.php">
            <img src="../../source/icon/pesanan1.svg" alt=""><span class="nav-teks">Keranjang</span>
        </a>
        <a href="profil.php">
            <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
        </a>
    </nav>
</div>

<div class="back">
    <a href="pembeli.php" class="btn-back">
        <img src="../../source/icon/kembali.svg" alt="Kembali">
    </a>
    <h2>Detail Menu</h2>
</div>

<div class="container">

    <div class="gambar">
        <img src="../../source/gambar_menu/<?php echo $data['FOTO_MENU']; ?>" alt="<?php echo $data['NAMA_MENU']; ?>">
    </div>

    <div class="info">
        <h2><?php echo $data['NAMA_MENU']; ?></h2>

        <div class="row-info">
            <div class="stock">
                Stok: <span id="stok"><?= $data['STOK']; ?></span>
            </div>
            <div class="rating">
               ★ <span id="rate"><?= $data['RATING'] ?></span> 
            </div>
        </div>

        <div class="description-box">
            <h3>Deskripsi</h3>
            <p><?php echo nl2br($data['DESK']); ?></p>
        </div>

        <form id="form-data">
            <input type="hidden" name="id_menu" value="<?php echo $data['ID_MENU']; ?>">

            <div class="row-harga-qty">
                <div class="harga">
                    Rp <?php echo number_format($data['HARGA'], 0, ',', '.'); ?>
                </div>

                
            </div>

            <div id="notif"></div>

            <div class="footer-keranjang"> 
                <button type="submit"  class="icon-badge"> <!--name="add_to_cart" --> 
                    Tambah Ke Keranjang
                </button>
                <button type="submit" name="buy_now" id="btnCheckout">
                    Beli Sekarang
                </button>
            </div>
        </form>
        
        <div id="rating">

            <br>
            <h2>tulis rating mu gan:D</h2>
            <!-- ini bagian rating wak,kalau udah buat tb transaksi nanti revisi dikit alur logika nya:D -->
            <div id="form-rate">
                <form action="pro_tesrate.php" method="post">
                    <input type="hidden" name="id_menu" value="<?= $data['ID_MENU'] ?>">
                    <input type="hidden" name="id_user" value="<?= $iduser ?>">
                    <input type="hidden" name="id_kantin" value="<?= $data['ID_KANTIN'] ?>">    
                    <label>rating coy:D</label>
                    <input type="number" name="rating" max="5" min="0" value="0">
                    <br>
                    <label>komentar gan:D</label>
                    <textarea name="desk" id="desk"></textarea>
                    <button type="submit" name="submit">kirim:D</button>
                    
                </form>
            </div>
            <br>
            
            <h2>rating</h2>

            <?php 
                $sql = "SELECT rating.*, users.NAMA_LENGKAP 
                            FROM rating 
                            INNER JOIN users ON rating.ID_USER = users.ID 
                            WHERE rating.ID_MENU = '$id'";
                $query = $conn->query($sql);

                while($row = $query->fetch_assoc()):
            ?>
                <p>user:<?= $row['NAMA_LENGKAP'] ?></p>
                <p>rate: <?= $row['RATING'] ?></p>
                <p>komentar:<?= $row['DESK'] ?></p>
                <br>
            <?php endwhile ?>
        </div>
    </div>
</div>


</body>
</html>