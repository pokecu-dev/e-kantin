<?php
require_once '../include/koneksi.php';

if (!isset($_GET['id'])) {
    echo "Menu tidak ditemukan!";
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM tb_menu WHERE ID_MENU='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ada!";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Order</title>
    <link rel="stylesheet" href="style_order.css">
    <style>
        .logo-desktop {
    display: flex;
    padding: 30px 0 0 10px;
}



.logo-desktop img {
    width: auto;
    height: 40px;
    z-index: 1000;
    position: fixed;
   
}



.top-nav {
    position: fixed;
    top: 0;
    width: 100%;
    height: 60px;
    background: #FFFCFA;
    display: flex;
    align-items: center;
    padding: 0 0px;
    flex-shrink: 0;
    z-index: 999;
    /* border-radius: 0 0 16px 16px; */
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
}

.menu {
    display: flex;
    gap: 20px;
    margin-left: auto;
    margin-right: 20px;

}

.menu a {
    display: flex;
    align-items: center;
}

.nav-teks {
    font-family: 'Poppins', sans-serif;
    padding: 5px 0 0 4px;
    /* font-weight: 400px; */

}

.top-nav a {
    padding: 10px;
    font-size: 22px;
    color: #aaa;
    transition: 0.3s;
    text-decoration: none;
}

.top-nav a:hover {
    color: #F47B20;
}

.logo-mobile img {
    width: auto;
    height: 30px;
    padding: 20px 0 0 20px;

}

@media (min-width: 768px) {
    .logo-mobile {
        display: none;
    }

    .logo-desktop {
        display: flex;
        align-items: center;
        z-index: 1000;
    }
}

@media (max-width: 780px) {

    .logo-desktop {
        display: none;
    }

    .logo-mobile {
        display: flex;
        align-items: center;
        padding: 30px 0 20px 25px;
        width: auto;
        height: 40px;
    }

    .logo-mobile img {
        height: 20vw;
        width: auto;
        max-height: 65px;
        min-height: 45px;
    }


    .top-nav {
        position: fixed;
        align-items: center;
        bottom: 0;
        left: 0;
        right: 0;
        top: auto;
        width: 100%;
        display: flex;
        height: 70px;
        flex-direction: row;
        justify-content: space-evenly;
        /* margin: 0; */
        padding: 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 0 0;
    }
    

    .menu {
        margin: 0;
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .menu a {
        margin: 30px;
    }

    .top-nav span {
        display: none;


    }

}

h2{
    color: white;
}

    </style>
</head>
<body>

<div class="logo-mobile">
        <img src="../../source/icon/logo.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../../source/icon/logo1.svg" alt="KantinKita">
    </div>
    <!-- --------/LOGO------------ -->
    <div class="top-nav">
        <nav class="menu" >
            <a href="penjual.php">
                <img src="../../source/icon/home2.svg" alt=" home"> <span class="nav-teks">History</span>
            </a>
            <a href="Keranjang.php">
                <img src="../../source/icon/pesanan1.svg" alt=""><span class="nav-teks">Edit</span>
            </a>
            <a href="profil.php">
                <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>
</div>
<h2>Pesan Menu</h2>

<div class="back">
        <a href="pembeli.php">←</a>
    </div>

<div class="container">


    <div class="gambar">
        <img src="/source/gambar_menu/<?php echo $data['FOTO_MENU']; ?>">
    </div>

    <div class="info">
        <h2><?php echo $data['NAMA_MENU']; ?></h2>
        <p class="rating">Rating 5,5 ★★★</p>
    </div>

    <div class="description-box">
        <h3>Deskripsi</h3>
        <p><?php echo nl2br($data['DESK']); ?></p>
    </div>


    <div class="jumlah">
        <button onclick="kurang()">-</button>
        <input type="text" id="qty" value="1">
        <button onclick="tambah()">+</button>
    </div>

    <div class="harga">
        <p>Rp <?php echo number_format($data['HARGA'],0,',','.'); ?></p>
        <p>Stok: <?php echo $data['STOK']; ?></p>
    </div>

    <form action="keranjang.php" method="POST" class="bottom-bar">
        <input type="hidden" n ame="id_menu" value="<?php echo $data['ID_MENU']; ?>">
       <button type="submit">Tambah ke Keranjang</button>
    </form>

</div>

</body>
</html>