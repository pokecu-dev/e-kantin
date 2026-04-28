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
    <title>Keranjang</title>
    <link rel="stylesheet" href="style_order.css">
</head>
<body>

<div class="container">

    <h2>Pesan Menu</h2>

    <div class="container">

    <!-- BACK -->
    <div class="back">
        <a href="pembeli.php">←</a>
    </div>

    <!-- GAMBAR -->
    <div class="gambar">
        <img src="/source/gambar_menu/<?php echo $data['FOTO_MENU']; ?>">
    </div>

    <!-- NAMA + RATING -->
    <div class="info">
        <h2><?php echo $data['NAMA_MENU']; ?></h2>
        <p class="rating">Rating 5,5 ★★★</p>
    </div>

    <div class="description-box">
        <h3>Description</h3>
        <p><?php echo nl2br($data['DESK']); ?></p>
    </div>

    <!-- JUMLAH -->
    <div class="jumlah">
        <button onclick="kurang()">-</button>
        <input type="text" id="qty" value="1">
        <button onclick="tambah()">+</button>
    </div>

    <!-- HARGA & STOK -->
    <div class="harga">
        <p>Rp <?php echo number_format($data['HARGA'],0,',','.'); ?></p>
        <p>Stok: <?php echo $data['STOK']; ?></p>
    </div>



    <form action="proses_order.php" method="POST">
        <input type="hidden" name="id_menu" value="<?php echo $data['ID_MENU']; ?>">

        <button type="submit">Pesan Sekarang</button>
    </form>

</div>

</body>
</html>