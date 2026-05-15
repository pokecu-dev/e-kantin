<?php
session_start();
require_once '../include/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: ./login.php');
    exit();
}

// ADD TO CART
if (isset($_POST['add_to_cart'])) {

    $id_menu = (int)($_POST['id_menu'] ?? 0);
    $qty = (int)($_POST['qty'] ?? 1);
    $id_user = (int)$_SESSION['id_user'];

    if ($id_menu > 0 && $qty > 0) {

        $cek_keranjang = mysqli_query($conn,
            "SELECT * FROM keranjang 
            WHERE id_menu='$id_menu' 
            AND id_user='$id_user'"
        );

        if (mysqli_num_rows($cek_keranjang) > 0) {

            mysqli_query($conn,
                "UPDATE keranjang 
                SET qty = qty + $qty 
                WHERE id_menu='$id_menu' 
                AND id_user='$id_user'"
            );

        } else {

            mysqli_query($conn,
                "INSERT INTO keranjang(id_user,id_menu,qty)
                VALUES('$id_user','$id_menu','$qty')"
            );
        }
    }

    header('Location: keranjang.php');
    exit();
}

$id_user_aktif = (int)$_SESSION['id_user'];

$query = mysqli_query($conn, "
    SELECT 
        k.*,
        m.NAMA_MENU,
        m.HARGA,
        m.FOTO_MENU,
        ka.NAMA_KANTIN
    FROM keranjang k
    JOIN tb_menu m ON k.id_menu = m.id_menu
    JOIN list_kantin ka ON m.ID_KANTIN = ka.ID
    WHERE k.id_user = $id_user_aktif
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>

    <link rel="stylesheet" href="style.css">

    <style>

        .body{
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #222;
        }

        .container{
            margin: 120px 15px 40px;
        }

        .back {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            margin-top: 20px; 
            position: relative;
            z-index: 10;
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

        .text{
            margin-bottom: 20px;
        }

        .parent{
            background: #dac8b9;
            padding: 15px;
            border-radius: 16px;
        }

       
        .produk{
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            gap: 15px;
        }

        .header-tabel{
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            gap: 15px;

            background: #fff5eb;
            padding: 15px;
            border-radius: 10px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .produk{
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 15px;
        }

        .div1{
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .div1 img{
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
        }

        .div1 p{
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .harga-tabel{
            font-weight: 600;
        }

        .nama-kantin{
            font-size:14px;
            color:#777;
            margin:4px 0;
        }

        .jumlah{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .jumlah button{
            width: 35px;
            height: 35px;
            border: none;
            background: #F47B20;
            color: white;
            border-radius: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        .jumlah input{
            width: 45px;
            height: 35px;
            text-align: center;
            font-size: 16px;
            border: none;
            background: #f3f3f3;
            border-radius: 10px;
            outline: none;
        }
        

        .subtotal{
            font-weight: 700;
            text-align: right;
        }

        .checkout-box{
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 16px;
        }

        .checkout-btn{
            width: 100%;
            height: 45px;
            border: none;
            border-radius: 12px;
            background: #F47B20;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
        }

        @media (max-width: 768px){

            .header-tabel{
                display: none;
            }

            .produk{
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .subtotal,
            .harga-tabel{
                text-align: left;
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
    <nav class="menu" >
            <a href="penjual.php">
                <img src="../../source/icon/home1.svg" alt=" home"> <span class="nav-teks">Beranda</span>
            </a>
            <a href="keranjang.php">
                <img src="../../source/icon/pesanan2.svg" alt=""><span class="nav-teks">Keranjang</span>
            </a>
            <a href="profil.php">
                <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>
</div>

<div class="container">

    <div class="back">
        <a href="pembeli.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>

        <h2>Keranjang Saya</h2>
    </div>

    <div class="parent">

        <div class="header-tabel">
            <div>Produk</div>
            <div>Harga Satuan</div>
            <div>Jumlah</div>
            <div>Subtotal</div>
        </div>

        <?php
        $total = 0;
        ?>

        <?php if(mysqli_num_rows($query) > 0) { ?>

            <?php while($data = mysqli_fetch_array($query)) { ?>

                <?php
                $subtotal = $data['HARGA'] * $data['qty'];
                $total += $subtotal;
                ?>

                <div class="produk">

                    <div class="div1">

                        <img src="../../source/gambar_menu/<?php echo $data['FOTO_MENU']; ?>">

                        <div>
                            <p><?php echo $data['NAMA_MENU']; ?></p>

                            <p class="nama-kantin">
                                <?php echo $data['NAMA_KANTIN']; ?>
                            </p>
                        </div>

                    </div>

                    <div class="harga-tabel">
                        Rp <?php echo number_format($data['HARGA'],0,',','.'); ?>
                    </div>

                    <div class="jumlah">

                        <button>-</button>

                        <input
                            type="number"
                            value="<?php echo $data['qty']; ?>"
                            readonly
                        >

                        <button>+</button>

                    </div>

                    <div class="subtotal">
                        Rp <?php echo number_format($subtotal,0,',','.'); ?>
                    </div>

                </div>

            <?php } ?>

            <div class="checkout-box">

                <h3>Total Belanja</h3>

                <h2>
                    Rp <?php echo number_format($total,0,',','.'); ?>
                </h2>

                <button class="checkout-btn">
                    Checkout Sekarang
                </button>

            </div>

        <?php } else { ?>

            <p>Keranjang masih kosong</p>

        <?php } ?>

    </div>

</div>

</body>
</html>