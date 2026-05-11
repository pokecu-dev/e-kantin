<?php
session_start();
require_once '../include/koneksi.php';

// Cek apakah ada kiriman data dari tombol Tambah Ke Keranjang
if (isset($_POST['add_to_cart'])) {
    $id_menu = $_POST['id_menu'];
    $qty = $_POST['qty'];
    $id_user = $_SESSION['id_user']; 
    $cek_keranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_menu = '$id_menu' AND id_user = '$id_user'");
    
    if (mysqli_num_rows($cek_keranjang) > 0) {
        mysqli_query($conn, "UPDATE keranjang SET qty = qty + $qty WHERE id_menu = '$id_menu' AND id_user = '$id_user'");
    } else {
        mysqli_query($conn, "INSERT INTO keranjang (id_user, id_menu, qty) VALUES ('$id_user', '$id_menu', '$qty')");
    }
    
    header("Location: keranjang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            color: black;
            font-family: 'Poppins', sans-serif;

        }

        .container {
            margin: 50px 5px 0 5px;
        }

        .parent {
            background-color: #dac8b9;
            padding: 15px;
            border-radius: 10px;


        }

        .text {
            margin-left: 10px;
        }

        /* Mengatur baris header dan produk agar sama persis */
        .header-tabel,
        .produk {
            display: grid;
            /* 4 Kolom: kolom pertama lebih lebar (2fr), sisanya sama rata (1fr) */
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            gap: 10px;
            padding: 8px;
            overflow-x: hidden;
            overflow-x: auto;
        }

        /* Warna background */
        .header-tabel {
            background: #fff5eb;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .produk {

            border-bottom: 1px solid #492509; 
        }


        /* Styling gambar */
        .div1 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .div1 img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
        }

        .btn {
            border: none;
            outline: none;
            font-size: 14px;
            height: 40px;
            border-radius: 5px;
            color: white;
            margin: 20px 0 15px;
            background-color: #F47B20;
            box-shadow: 0 2px 5px #492509;
        }

        .card {
            background-color: #fff5eb;
            border-radius: 5px;
        }

        .jumlah {
        display: flex;
        align-items: center;
        justify-content: center;
        
        gap: 10px;
        margin-top: 0px;
    }

    .jumlah button {
        width: 35px;
        height: 35px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background:  #F47B20;
        color: white;
        font-size: 20px;
        border-radius: 12px;
        cursor: pointer;
        line-height: 0;
    }

    .jumlah input {
        width: 40px;
        height: 35px;
        text-align: center;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    </style>
</head>

<body>
    <div class="top-nav">
        <nav class="menu">
            <a href="pembeli.php">
                <img src="../../source/website1/icon/home1.svg" alt=" home"> <span class="nav-teks">Beranda</span>
            </a>
            <a href="#">
                <img src="../../source/website1/icon/pesanan2.svg" alt=""><span class="nav-teks">Keranjang</span>
            </a>
            <a href="#">
                <img src="../../source/website1/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>
    </div>
    <div class="container">
        <h2 class="text">Keranjang Saya</h2>
        <div class="parent">
            <div class="header-tabel">
                <div>Produk</div>
                <div>Harga Saatuan</div>
                <div>Jumlah</div>
                <div>Subtotal</div>
            </div>
            <div class="card">

    <?php
   $id_user_aktif = $_SESSION['id_user'];
   $query = mysqli_query($conn, "
        SELECT k.*, m.NAMA_MENU, m.HARGA, m.FOTO_MENU 
        FROM keranjang k
        JOIN tb_menu m ON k.id_menu = m.id_menu
        WHERE k.id_user = $id_user_aktif
    ");

     if(mysqli_num_rows($query) > 0) {

        while($data = mysqli_fetch_array($query)) {

    ?>

        <div class="produk">
            <div class="div1">
                <img src="../../source/gambar_menu/<?php echo $data['FOTO_MENU']; ?>">
                <div>
                    <p><?php echo $data['NAMA_MENU']; ?></p>
                </div>
            </div>

            <div class="harga-tabel">
                Rp <?php echo number_format($data['HARGA'], 0, ',', '.'); ?>
            </div>

           <div class="jumlah">
                <button type="button" class="btn-qty" onclick="ubahQty('kurang', <?php echo $data['id_keranjang']; ?>)">-</button>
                <input type="number" name="qty" value="<?php echo $data['qty']; ?>" readonly>
                <button type="button" class="btn-qty" onclick="ubahQty('tambah', <?php echo $data['id_keranjang']; ?>)">+</button>
            </div>

            <div class="subtotal">
                <?php 
                    $subtotal = $data['HARGA'] * $data['qty'];
                    echo "Rp " . number_format($subtotal, 0, ',', '.'); 
                ?>
            </div>
            
            <?php }
            } else {
                // Tampilan jika keranjang masih kosong
                echo "<p style='text-align:center; padding:20px;'>Wah, keranjangmu masih kosong nih!</p>";
            }
            ?>
        </div>

    </div>

</body>
</html>
        