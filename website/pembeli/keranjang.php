<?php
session_start();
require_once '../include/koneksi.php';

// Proteksi halaman: Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

// PROSES TAMBAH KE KERANJANG (ADD TO CART)
// if (isset($_POST['add_to_cart'])) {

//     $id_menu = (int)($_POST['id_menu'] ?? 0);
//     $qty = (int)($_POST['qty'] ?? 1);
//     $id_user = (int)$_SESSION['id_user'];

//     if ($id_menu > 0 && $qty > 0) {

//         $cek_keranjang = mysqli_query($conn,
//             "SELECT * FROM keranjang 
//             WHERE id_menu='$id_menu' 
//             AND id_user='$id_user'"
//         );

//         if (mysqli_num_rows($cek_keranjang) > 0) {
//             mysqli_query($conn,
//                 "UPDATE keranjang 
//                 SET qty = qty + $qty 
//                 WHERE id_menu='$id_menu' 
//                 AND id_user='$id_user'"
//             );
//         } else {
//             mysqli_query($conn,
//                 "INSERT INTO keranjang(id_user,id_menu,qty)
//                 VALUES('$id_user','$id_menu','$qty')"
//             );
//         }
//     }

//     header('Location: keranjang.php');
//     exit();
// }

// AMBIL DATA BARANG DI KERANJANG USER AKTIF
$id_user_aktif = (int)$_SESSION['id_user'];

$query = mysqli_query($conn, "
    SELECT 
        k.*,
        m.NAMA_MENU,
        m.HARGA,
        m.FOTO_MENU,
        m.STOK,
        ka.NAMA_KANTIN
    FROM keranjang k
    JOIN tb_menu m ON k.id_menu = m.ID_MENU
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
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .container {
           width: 95%;
            max-width: 1100px;
            margin: 0 auto 40px;
            padding: 30px;
        }

        .back {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 0;
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

        .parent {
            background: #dac8b9;
            padding: 15px;
            border-radius: 16px;
        }

        .header-tabel {
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

        .produk {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            gap: 15px;
            position: relative;
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 15px;
        }

        .div1 {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .div1 img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
        }

        .div1 p {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .harga-tabel {
            font-weight: 600;
        }

        .nama-kantin {
            font-size: 14px;
            color: #777;
            margin: 4px 0;
        }

        .jumlah {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .jumlah button {
            width: 35px;
            height: 35px;
            border: none;
            background: #F47B20;
            color: white;
            border-radius: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        .jumlah input {
            width: 45px;
            height: 35px;
            text-align: center;
            font-size: 16px;
            border: none;
            background: #f3f3f3;
            border-radius: 10px;
            outline: none;
        }

        .subtotal {
            font-weight: 700;
            text-align: right;
        }

        .checkout-box {
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 16px;
        }

        .checkout-btn {
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

        @media (max-width: 768px) {
            .header-tabel {
                display: none;
            }
            .produk {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .subtotal, .harga-tabel {
                text-align: left;
            }
        }

        @media (min-width: 769px){

            .checkout-box{
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .grand-total-container{
                margin-top: 0 !important;
            }

            .checkout-btn{
                width: 250px;
                margin-top: 0;
            }

        }

        .btn-hapus img {
            width: 22px;
            height: 22px;
            cursor: pointer;
        }

        .btn-hapus {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 15px;
            right: 15px;
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
            <img src="../../source/icon/home1.svg" alt="home"> <span class="nav-teks">Beranda</span>
        </a>
        <a href="keranjang.php">
            <img src="../../source/icon/pesanan2.svg" alt=""><span class="nav-teks">Keranjang</span>
        </a>
        <a href="Pesanan.php">
            <img src="../../source/icon/proses.svg" alt=""><span class="nav-teks">Pesanan</span>
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

            <?php while($data = mysqli_fetch_array($query)) { 
                $subtotal = $data['HARGA'] * $data['qty'];
                $total += $subtotal;
            ?>

                <div class="produk">

                    <div class="div1">
                        <img src="../../source/gambar_menu/<?php echo $data['FOTO_MENU']; ?>" alt="Foto Menu">
                        <div>
                            <p><?php echo $data['NAMA_MENU']; ?></p>
                            <p class="nama-kantin">
                                <?php echo $data['NAMA_KANTIN']; ?>
                            </p>
                            <!-- Tempat menyimpan stok max secara hidden untuk kebutuhan Javascript -->
                            <span class="stok-maks" style="display:none;"><?php echo $data['STOK']; ?></span>
                        </div>
                    </div>

                    <a href="hapus_keranjang.php?id=<?php echo $data['id_keranjang']; ?>" class="btn-hapus">
                        <img src="../../source/icon/sampah.svg" alt="hapus">
                    </a>

                    <div class="harga-tabel">
                        Rp <?php echo number_format($data['HARGA'], 0, ',', '.'); ?>
                    </div>

                    <div class="jumlah">
                        <!-- Mengirim data objek tombol (this) agar fungsi JS tidak bingung baris mana yang diklik -->
                        <button type="button" data-id="<?= $data['id_menu'] ?>" onclick="UpdateQTY(this, -1)">-</button>
                        <input type="number" name="qty" class="qty-input" value="<?php echo $data['qty']; ?>" min="1" max="<?php echo $data['STOK']; ?>" readonly>
                        <button type="button" data-id="<?= $data['id_menu'] ?>" onclick="UpdateQTY(this, 1)">+</button>
                    </div>

                    <div class="subtotal">
                        <p id="subtotal">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></p>
                        
                    </div>

                </div>

            <?php } ?>

            <div class="checkout-box">
                <div class="grand-total-container" style="margin-top: 20px; font-weight: bold; font-size: 20px;">
                    Total Belanja: <span id="total-belanja">Rp 0</span>
                </div>
                
                <button class="checkout-btn">
                    <a href="beli.php" class="checkout" </a>
                    Checkout Sekarang
                </button>
            </div>

        <?php } else { ?>
            <div style="background: white; padding: 25px; border-radius: 16px; text-align: center;">
                <p>Keranjang masih kosong</p>
            </div>
        <?php } ?>

    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        totalBelanja();
    });

    function UpdateQTY(btn, step) {
        // Cari container produk terdekat dari tombol yang diklik
        let produkDiv = btn.closest('.produk');
        let inputQTY = produkDiv.querySelector('.qty-input');
        let maxStock = parseInt(produkDiv.querySelector('.stok-maks').innerText);
        
        let newVal = parseInt(inputQTY.value) + step;

        subtotalVal = newVal;
        const hargaKotorStr = produkDiv.querySelector('.harga-tabel');
        let harga = hargaKotorStr.innerText.slice(3).replace(/\./g,''); //regex "/\./g" , /.../ -> adalah format regex, \ -> mengubah chara khusus regex menjadi chara biasa, . -> simbol yang di cari, g -> flag yang berarti global
        // singkat nya fungsi regex di atas untuk mencari ".",format replace(chara_before, chara_setelah_replace) jarang jarang make comment:v

        harga = Number(harga);

        const subtotal = produkDiv.querySelector('#subtotal');
        

        
        // Cek batas minimum (1) dan batas maksimum stok toko
        if (newVal >= 1 && newVal <= maxStock) {
            inputQTY.value = newVal;
            newValST = newVal * harga;
            newValST = newValST.toLocaleString('id-ID');
            console.log(newValST);
            subtotal.innerText = `Rp ${newValST}`;
            totalBelanja(); 

            let idmenu = btn.getAttribute('data-id');

            fetch('./up_keranjangDB.php',{
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded' 
                },
                body: `id_menu=${idmenu}&qty=${newVal}`
            })
            .then(Response => Response.json())
            .then(data => {
                console.log("Mantap berhasil:", data);
            })
            .catch(error => {
                console.error("Waduh error:", error);
            });
            

        } else if (newVal > maxStock) {
            alert("Maaf, stok tidak mencukupi!");
        }
    }

    function totalBelanja(){
        let semuaSubTotal = document.querySelectorAll('.subtotal');
        let grandtotal = 0;
        const totalHarga = document.getElementById('total-belanja');

        semuaSubTotal.forEach(function(elemen){
            let angkaStr = elemen.innerText.slice(3).replace(/\./g,'');
            let angka = Number(angkaStr);

            grandtotal += angka;
        });

        let formatRibuan = grandtotal.toLocaleString('id-ID');

        if (totalHarga !== null) {
        totalHarga.innerText = `Rp ${formatRibuan}`;

        }
    }

</script>

</body>
</html>