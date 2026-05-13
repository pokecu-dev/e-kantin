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
        m.FOTO_MENU
    FROM keranjang k
    JOIN tb_menu m ON k.id_menu = m.id_menu
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

        body{
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #222;
        }

        .container{
            margin: 40px 15px;
        }

        .text{
            margin-bottom: 20px;
        }

        .parent{
            background: #dac8b9;
            padding: 15px;
            border-radius: 16px;
        }

        .header-tabel,
        .produk{
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            gap: 15px;
        }

        .header-tabel{
            background: #fff5eb;
            padding: 15px;
            border-radius: 10px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .produk{
            background: white;
            padding: 20px;
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
        }

        .subtotal{
            font-weight: 700;
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

    </style>
</head>

<body>

<div class="top-nav">
    <nav class="menu">

        <a href="pembeli.php">
            <img src="../../source/website1/icon/home1.svg">
            <span class="nav-teks">Beranda</span>
        </a>

        <a href="#">
            <img src="../../source/website1/icon/pesanan2.svg">
            <span class="nav-teks">Keranjang</span>
        </a>

        <a href="#">
            <img src="../../source/website1/icon/user1.svg">
            <span class="nav-teks">Profil</span>
        </a>

    </nav>
</div>

<div class="container">

    <h2 class="text">Keranjang Saya</h2>

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