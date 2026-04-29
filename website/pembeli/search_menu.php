<?php 
require_once '../include/koneksi.php'; 


    if (isset($_GET['search'])) {

        $search = $_GET['search'];

        $query = "SELECT * FROM tb_menu 
           
              WHERE ID_KANTIN = 1  
              AND NAMA_MENU LIKE '%$search%'";

        $result = mysqli_query($conn, $query);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>
    <style>
         .div1 {
            /* grid-row: span 1 / span 1; */
            width: 100%;
            color: black;
            justify-content: center;
            text-align: center;
        }

        .parent {
            font-family: 'Poppins', sans-serif;
            display: grid;
            grid-template-columns: auto auto auto auto auto;

            /* justify-content: center;
    /* horizontal 
    align-items: center; */
            /* flex-wrap: wrap; */

        }

        /* .div1 a {
            display: block;
            text-align: center;
            text-decoration: none;
            color: white;
            background-color: #B09B83;
            padding: 10px 25px 10px 25px;
            font-size: 20px;
            border-radius: 50px;
            place-items: center;
            justify-self: center;

        } */

        .div1 img {

            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            display: block;

        }

        .nama {
            font-size: 14px;
            font-weight: 600;
            margin-top: 6px;
        }

        .rating {
            font-size: 12px;
            color: #777;
        }

        .harga {
            color: #ff7a00;
            font-weight: 700;
            font-size: 14px;
        }

        .btn {
            background: #ff7a00;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            margin-top: 5px;
            cursor: pointer;
        }

        .card-menu {
            width: 90%;
            background-color: white;
            border-radius: 10px;
            align-items: center;
            display: flex;
            flex-direction: column;
            padding: 10px;
            margin: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    
</head>

<body>

 <!-- LOGO -->
    <div class="logo-mobile">
        <img src="../icon/logo.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../icon/logo1.svg" alt="KantinKita">
    </div>
    <!-- --------/LOGO------------ -->
    <div class="top-nav">
        <nav class="menu">
            <a href="#">
                <img src="../icon/home2.svg" alt=" home"> <span class="nav-teks">Beranda</span>
            </a>
            <a href="#">
                <img src="../icon/pesanan1.svg" alt=""><span class="nav-teks">Pesanan</span>
            </a>
            <a href="#">
                <img src="../icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>

    </div>

<div class="parent"> <!-- INI PARENT -->

<?php
        
        while ($data = mysqli_fetch_assoc($result)) {
    ?>
<div class="card-menu">
            <div class="div1">

               <img src="/source/gambar_menu/<?php echo $row['FOTO_MENU']; ?>">
                    <h3><?php echo $row['NAMA_MENU']; ?></h3>
                    <div class="rating">Rating 5,5 ★★★</div>
                    
                    <p>Rp <?php echo number_format($row['HARGA'], 0, ',', '.'); ?></p>
                <button class="btn">Pesan</button>
            </div>
</div>
    <?php
        }
    ?>
    </div>
</body>

</html>