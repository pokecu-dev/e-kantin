<?php
session_start();
require_once '../include/koneksi.php';

if (!isset($_GET['id'])) {
    echo "Menu tidak ditemukan!";
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM tb_menu WHERE id_menu='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ada!";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Detail Menu</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    * {
        box-sizing: border-box;
    }
    .container {
        padding: 15px;
        width: 95%;
        max-width: 500px; 
        background: #ffffff;  
        border-radius: 20px;
        margin:20px  auto;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .top-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px;
        color: white;
        font-weight: 600;
    }

    .menu-container{
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(250px,1fr));
        gap:15px;
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

    .gambar {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 10px;
    }

    .gambar img {
        width: 100%;
        max-width: 400px;
        height: auto;
        display: block;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border-radius: 25px;
        border: 3px solid #6f4f36;
        margin: 0 auto;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .info h2 {
        margin: 10px 0 5px;
        font-size: 18px;
        text-align: center;
    }

    .row-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
    }

    .stock {
        font-size: 14px;
        color: #333;
    }

    .id-kantin {
        font-size: 14px;
        color: #333;
    }
    
    .rating {
        color: #F47B20;
        font-size: 18px;
    }

    .description-box {
        margin-top: 15px;
        background:  #ffffff;
        padding: 15px;
        border-radius: 12px;
        font-size: 14px;
        color: black;
        border: 1px solid #f47b20;
        line-height: 1.5;
    }

    .qty-box {
        margin-top: 20px;
    }

    .row-harga-qty {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        justify-content: center; 
        align-items: center;
        margin-top: 15px;
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

    .harga {
        font-size: 18px;
        color: #333;
    }

    .harga::after {
        content: "|";
        margin-left: 10px;
        color: rgba(0,0,0,0.3); 
    }

   .footer-keranjang {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        padding: 10px 15px;
        border-top: 1px solid #eee;
        margin-top: 25px;
    }

    .icon-badge {
        position: relative;
        display: flex;
        align-items: center;
        background: transparent;
        gap: 8px;
        cursor: pointer;
        color: #1A1A1A;
        border: 1px solid #F47B20;
    }

    .badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #F47B20;
        color: white;
        font-size: 10px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    #btnCheckout {
        background: #F47B20;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        font-size: 14px;
    }

    #btnCheckout:active {
        transform: scale(0.98);
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
    <!-- --------/LOGO------------ -->
    <div class="top-nav">
        <nav class="menu" >
            <a href="penjual.php">
                <img src="../../source/icon/home2.svg" alt=" home"> <span class="nav-teks">Beranda</span>
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
            <img src="/source/gambar_menu/<?php echo $data['FOTO_MENU']; ?>">
        </div>

        <div class="info"> 
            <h2><?php echo $data['NAMA_MENU']; ?></h2>
        </div>

        <div class="row-info">
            <div class="stock">
                Stock: <span id="stok"><?= $data['STOK']; ?></span>
            </div>

            <div class="rating">
                5,5 ★★★
            </div>

        </div>

        <div class="description-box">
            <h3>Deskripsi</h3>
            <p><?php echo nl2br($data['DESK']); ?></p>
        </div>

        <form action="keranjang.php" method="POST">
        <input type="hidden" name="id_menu" value="<?php echo $data['ID_MENU']; ?>">

        <div class="row-harga-qty">
            
            <div class="harga">
                Rp <?php echo number_format($data['HARGA'], 0, ',', '.'); ?>
            </div>

            <div class="jumlah">
                <button type="button" onclick="UpdateQTY(-1)">-</button>
                <input type="number"  name="qty" id="qty" value="1" min="1" max="<?php echo $data['STOK']; ?>" readonly>
                <button type="button" onclick="UpdateQTY(1)">+</button>
            </div>

        </div>

        <!-- Baris baru untuk layout Checkout/Keranjang -->
        <div class="footer-keranjang">
        <button type="submit" name="add_to_cart"class="icon-badge">
                <span>Tambah Ke Keranjang</span>
            </button>

            <button type="submit" id="btnCheckout">
                Checkout
            </button>
        </div>
        <script>

        const inputQTY = document.getElementById("qty");
        const getstock =() => parseInt(document.getElementById("stok").innerText);

        function UpdateQTY(step){

            let currentStock = getstock();
            
            let newVal = parseInt(inputQTY.value) + step;

            if(newVal >= 1 && currentStock >= newVal){
                inputQTY.value = newVal;
            } else if (newVal > currentStock) {
                alert("Maaf, stok tidak mencukupi!");
            }
        }
            
        inputQTY.oninput = function(){
            let value = parseInt(this.value);
            let currentStock = getstock();

            if(this.value === "" || isNaN(value) || value < 1){
                this.value = 1;
            }
            else if(value > currentStock){
                this.value = currentStock;
            }

        }

</script>

</div>

</body>
</html>