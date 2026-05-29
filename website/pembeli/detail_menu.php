<?php
// session_start();
require_once '../include/koneksi.php';
require_once __DIR__ . "/../include/session/pembeliC.php";

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
            padding: 35px;
            width: 95%;
            max-width: 1100px; 
            background: #ffffff;  
            border-radius: 24px;
            margin: 120px auto 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: flex;
            gap: 40px;
            flex-direction: column;
        }

        .top-content{
            display:flex;
            gap:50px;
            align-items:flex-start;
            width: 100%;
        }

        .ulasan-section{
            margin-top:50px;
            border-top:1px solid #eee;
            padding-top:30px;
        }

        .ulasan-section h2{
            font-size:30px;
            margin-bottom:25px;
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
            height: 280px;
            display: block;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 0;
            background-color: #fff;
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
            border: 2px solid #E66A12;
            color: #E66A12;
        }

        .icon-badge:hover {
            background: #fff5eb;
            transform: scale(0.97);
        }

        #btnCheckout {
            background: #F47B20;
            color: white; 
            border: 2px solid #F47B20;
        }

        #btnCheckout:hover {
            background: #cc5d0e;
            border-color: #cc5d0e;
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
        .ulasan-section {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 30px;
        }

        .ulasan-section h2 {
            font-size: 22px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-ulasan {
            padding: 20px 0;
            border-bottom: 1px solid #f9f9f9;
        }

        .atas-ulasan {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .foto-user {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .foto-user img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            }

        .nama-user {
            font-size: 15px;
            font-weight: 600;
            color: #222;
        }

        .rating-bintang {
            margin-top: 2px;
            color: #E66A12;
            font-size: 13px;
        }

        .komen {
            margin-left: 62px;
            margin-top: 8px;
            color: #555;
            line-height: 1.6;
            font-size: 14px;
        }
        .kosong-ulasan {
            text-align: center;
            padding: 50px 20px;
            color: #888;
        }

        .icon-kosong {
            font-size: 50px;
            margin-bottom: 15px;
        }
        @media(max-width: 768px) {
            .back {
                /* Berikan margin top yang cukup agar lolos dari jeratan navbar yang melayang */
                margin: 100px auto 10px !important; 
                width: 90%; /* Menyesuaikan lebar di layar HP agar tidak terlalu mepet ke pinggir */
                padding: 10px 0;
            }

            .container {
                /* Sesuaikan margin top container agar jaraknya pas dengan tombol back di atasnya */
                margin: 20px auto 100px;
                padding: 20px;
            }

            .top-content {
                flex-direction: column;
                gap: 25px;
            }

            .gambar img {
                height: 280px;
            }

            .info h2 {
                font-size: 28px;
            }
            .harga {
                font-size: 24px;
            }

            .footer-keranjang {
                flex-direction: column;
                gap: 12px;
            }

            .icon-badge, #btnCheckout {
                width: 100%;
                height: 50px;
            }

            .komen {
                margin-left: 0;
                margin-top: 12px;
                color: #555;
                line-height: 1.6;
                font-size: 14px;

                word-wrap: break-word;
                overflow-wrap: break-word;
                word-break: break-word;
                white-space: normal;
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
                <li><a href="pembeli.php" class="active">Beranda</a></li>
                <li><a href="keranjang.php">Akun</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

<div class="back">
    <a href="pembeli.php" class="btn-back">
        <img src="../../source/icon/kembali.svg" alt="Kembali">
    </a>
    <h2>Detail Menu</h2>
</div>

<div class="container">

     <div class="top-content">

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
               ★ <span id="rating"><?= $data['RATING'] ?? "0.0" ?></span>
            </div>
        </div>

        <div class="description-box">
            <h3>Deskripsi</h3>
            <p><?php echo nl2br($data['DESK']); ?></p>
        </div>

        <form id="form-data" action="detail_menu.php?id=<?= $id; ?>" method="POST">
            <input type="hidden" name="id_menu" value="<?php echo $data['ID_MENU']; ?>">

            <div class="row-harga-qty">
                <div class="harga">
                    Rp <?php echo number_format($data['HARGA'], 0, ',', '.'); ?>
                </div>

                <div class="jumlah">
                    <button type="button" onclick="UpdateQTY(-1)">-</button>
                    <input type="number" name="qty" id="qty" value="1" min="1" max="<?php echo $data['STOK']; ?>" readonly>
                    <button type="button" onclick="UpdateQTY(1)">+</button>
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
        </div>
    </div>
    <div class="ulasan-section">
        <h2>Ulasan Pembeli</h2>

        <?php 
            $sql = "SELECT rating.*, users.NAMA_LENGKAP, users.FOTO_USERS 
                        FROM rating 
                        INNER JOIN users ON rating.ID_USER = users.ID 
                        WHERE rating.ID_MENU = '$id'";
            $query = $conn->query($sql);

            if($query->num_rows > 0):

            while($row = $query->fetch_assoc()):
        ?>

        <div class="card-ulasan">
            <div class="atas-ulasan">
            <div class="foto-user">
                <?php if(!empty($row['FOTO_USERS'])): ?>
                    <img src="../../source/fotopengguna/<?php echo $row['FOTO_USERS']; ?>">
                <?php else: ?>
                    <img src="../../source/fotopengguna/default.png">
                <?php endif; ?>
            </div>
                <div class="detail-user">
                    <div class="nama-user">
                        <?= htmlspecialchars($row['NAMA_LENGKAP']); ?>
                    </div>
                <div class="rating-bintang">
                    <?php
                    for($i=1;$i<=5;$i++){
                        echo ($i <= $row['RATING']) ? "★" : "☆";
                    }
                    ?>
                </div>
            </div>
            
        </div>
            
        <div class="komen">
            <?= htmlspecialchars($row['DESK']); ?>
        </div>

        </div>
        <?php endwhile;

        else:
        ?>

        <div class="kosong-ulasan">
            <div class="icon-kosong">
                💬
            </div>
            <h3>Belum ada ulasan</h3>
            <p>Jadilah pembeli pertama yang memberi rating ✨</p>
        </div>
    </div>
<?php endif; ?>

<script src="./../shared/js/script.js">

    const inputQTY = document.getElementById("qty");
    
    const getstock = () => parseInt(document.getElementById("stok").innerText);

    function UpdateQTY(step) {
        let currentStock = getstock();
        var newVal = parseInt(inputQTY.value) + step;

        if (newVal >= 1 && newVal <= currentStock) {
            inputQTY.value = newVal;
        } else if (newVal > currentStock) {
            alert("Maaf, stok tidak mencukupi!");
        }
    }

    inputQTY.oninput = function() {
        let value = parseInt(this.value);
        let currentStock = getstock();

        if (this.value === "" || isNaN(value) || value < 1) {
            this.value = 1;
        } else if (value > currentStock) {
            this.value = currentStock;
        }
    }


    // ajax
    document.getElementById('form-data').onsubmit = async (e) => {
        e.preventDefault();
        const notif = document.getElementById('notif');
        const dataform = new FormData(e.target);

        try{
            const response = await fetch('keranjangDB.php',{
                method:'POST',
                body: dataform
            })
            // console.log(1);

            const data = await response.json();
            // console.log(2);
            if(data.status === 'success'){
                window.location.href = './keranjang.php'; 
            }
            console.log(data.message);

        }
        catch(e){
            notif.innerText = "error:" + e.message;
        }
        
    }
    
</script>

</body>
</html>