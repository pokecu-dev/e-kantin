<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
     echo $_SESSION['status'];
    header("location: ../index.php");
    exit();
}
if ($_SESSION['role'] != "penjual")

    $nama = $_SESSION['nama_lengkap'];

// echo $nama . '<br> <br>';


// echo ' sebagai pembeli';



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>

        * {
            font-family:'Poppins', sans-serif ;
        }
        /* body {
            color: black;
            font-family: 'Poppins', sans-serif;
        } */

        .container {
            margin: 50px 5px 0 5px;
        }

        .parent {
            background-color: #dac8b9;
            padding: 15px;
            border-radius: 10px;
           
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
        .detail{
          
            justify-content: center;
            text-align: center;
            display: flex;
            margin: 0;
        }
        .detail small{
              text-align: center; 
               justify-content: center;
               margin: 0 auto;
        }
        .detail img {
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

        /* .div1 {
            color: black;
            justify-content: center;
            text-align: center;
            display: grid;
            width: 100%;
            align-items: center;
        } */

        /* {
            background: rgb(71, 45, 45);
            width: 140px;
            height: 45px;
            border-radius: 10px;
            list-style: none;
            justify-content: center;
            align-items: center;
            display: flex;
        } */
    </style>
</head>

<body>

    <div class="logo-mobile">
        <img src="../../source/website1/icon/logo.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../../source/website1/icon/logo1.svg" alt="KantinKita">
    </div>
    <!-- --------/LOGO------------ -->
    <div class="top-nav" style="text-align: center; margin-bottom: 0px;">
        <nav class="menu">
            <a href="penjual.php" style="margin: 0 5px; text-decoration: none ; color:#F47B20">
                <img src="../../source/website1/icon/pesanan2.svg" alt=""> 
                <span>History</span>
            </a>
            <a href="edit1.php" class="active" style="margin: 0 5px; text-decoration: none;">
                <img src="../../source/website1/icon/edit1.svg" alt="">
                <span>Edit</span>
            </a>
            <div class="dropdown-container">
                <a href="profil.php" style="margin: 0 5px; text-decoration: none;">
                    <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks">Profile</span>
                </a>
                <div class="dropdown-content">
                    <a href="profil.php">Profile</a>
                    <a href="./../logout.php">Keluar</a>
                </div>
            </div>

        </nav>
    </div>

    <!------------------------- PESANAN -------------------------->
    <div class="container">
        <h2 class="text">Daftar Pesanan</h2>
        <div class="parent">
            <div class="header-tabel">
                <div>Produk</div>
                <div>Payment</div>
                <div>Total</div>
                <div>Status</div>
            </div>
            <div class="card">
                <div class="produk">
                    <div class="div1">
                       <div class="detail">
                        <small class="antri">Antrean: 10</small>
                        <img src="nasi-goreng.jpg">
                       </div>
                        <div>
                            <p>Nasi Goreng</p>
                            <small>Varian: Spesial</small>
                        </div>
                    </div>

                    <div>CASH</div>

                    <div>8.000</div>

                    <div>
                        <button class="btn">Terima Pesanan</button>
                    </div>
                </div>

                <div class="produk">
                    <div class="div1">
                       <div class="detail">
                        <small class="antri">Antrean: 10</small>
                        <img src="nasi-goreng.jpg">
                       </div>
                        
                        <div>
                           
                            <p>Nasi Goreng</p>
                            <small>Varian: Spesial</small>
                        </div>
                    </div>

                    <div>CASH</div>

                    <div>8.000</div>

                    <div>
                        <button class="btn">Terima Pesanan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

   
</body>

</html>