<?php
    session_start();
//     if(!isset($_SESSION['status']) || $_SESSION['status'] != 'success'){
//         // echo $_SESSION['status'];
//         header("location: ../login.php");
//         exit();
//     }


// session_start();
// if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
//     // echo $_SESSION['status'];
//     header("location: ../login.php");
//     exit();
// }

//     echo ' sebagai pembeli';

// $nama = $_SESSION['nama_lengkap'];

// echo $nama . '<br> <br>';

// echo ' sebagai pembeli';

    require_once '../include/koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h1 {
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 1.2rem;
            line-height: 1.2;
            color: #F47B20 ;
        }

        .mencari {
            display: flex;
        }

        .search-box {
            width: 100%;
            position: relative;
            padding: 0;
        }

        .search {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border-radius: 30px;
            border: none;
            outline: none;
            display: flex;
            background-color: #ffffff;
            background-repeat: no-repeat;
            background-position: 15px center;
            /* Posisi ikon di kiri */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .iconsch {
            width: 20px;
            height: 20px;
        }

        .btn-search {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .container{
            padding:20px;
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding-top: 20px;
        }

        .menu-container{
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(220px,1fr));
            gap:12px;
        }

        .menu-card{
            background-color: white;
            border-radius:12px;
            padding:8px;
            box-shadow:0 3px 8px rgba(0,0,0,0.1);
        }

        .menu-card img{
            width:100%;
            aspect-ratio:1/1;
            object-fit:cover;
            border-radius:12px;
        }

        .nama{
            font-size:14px;
            font-weight:600;
            margin-top:6px;
            color: #1A1A1A;
        }

        .rating{
            font-size:14px;
            color:#F47B20;
            bold:600;
        }

        .p{
           font-size: 14px;
           color: #1A1A1A;
           margin-bottom: 15px;
        }

        .slider {
            border-radius: 20px;
            margin-top: 30px;
            margin-bottom: 40px;
            width: 100%;

            overflow-x: auto;  

            position: relative;
        }

        .slides {
            display: flex;
            flex: 0 0 90%;
            gap: 22px;
            overflow-x: auto;
            padding: 10px 20px;
            scroll-snap-type: x mandatory;
            scroll-padding: 20px;
            scrollbar-width: none; 
            -ms-overflow-style: none;
            flex-wrap: nowrap; 
        }
                
        .slide {
            flex: 0 0 85%;  
            aspect-ratio: 20/ 10;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;

            scroll-snap-align: center;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .kantin-btn {
            position: absolute;
            top: 80%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #F47B20;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            white-space: nowrap;
        }

        .slides::-webkit-scrollbar {
            display: none;
        }

        .parent {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(199px, 1fr)); 
            gap: 15px;
            width: 98%;
            padding: 0 10px;
            max-width: 1400px;
            justify-content: center; 
        }

        @media (max-width: 1024px) {
            .parent {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (max-width: 768px) {
            .parent {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .child {
            background: #ffffff;
            padding: 10px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .child:hover {
            transform: translateY(-5px); 
        }

        .child img {
            width: 100%; 
            height: 160px;          
            aspect-ratio: 1 / 1;   
            object-fit: cover;     
            border-radius: 15px;   
            margin-bottom: 10px;
        }

        .child h3 {
            font-size: 16px;
            margin: 10px 0 5px 0;
        }

        .menu-link{
            text-decoration:none;
            color:inherit;
            display:block;
        }

        .add-btn {
            text-decoration: none;
            display: flex;
            right: 10px;
            bottom: 126px;
            position: absolute;
            background:  #F47B20;
            color: #ffffff;
            width: 32px;
            height: 32px;
            border-radius: 5px;
            z-index:10;
            border: none;
            font-size: 25px;
            cursor: pointer;
            transition: transform 0.2s ease;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .add-btn:hover {
            transform: scale(1.1);
            background: #F47B20;
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
    </div>

    <div class="top-nav">
        <nav class="menu" >
            <a href="pembeli.php">
                <img src="../../source/icon/home2.svg" alt=" home"> <span class="nav-teks">Beranda</span>
            </a>
            <a href="Keranjang.php">
                <img src="../../source/icon/pesanan1.svg" alt=""><span class="nav-teks">Keranjang</span>
            </a>
            <a href="profil.php">
                <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>

    </div>
    <div class="container">

      <h1 class="teks">Cari Menu <b>Yang Kamu Inginkan!</b></h1>

        <div class="mencari">

            <form action="search_menu.php" method="GET">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Cari menu..." class="search">
                    <button type="submit" class="btn-search">
                        <img src="../../source/icon/search.svg" alt="" class="iconsch">
                        </button>
                </div>
            </form>

        </div>
    
        <div class="slider">
            <div class="slides">
                <?php 
                $result_kantin = mysqli_query($conn, "SELECT * FROM list_kantin");
                $no = 1;
                while ($row = mysqli_fetch_assoc($result_kantin)) {
                ?>
                    <div class="slide">
                        <img src="../../source/foto_kantin/<?php echo $row['foto_kantin']; ?>" alt="Gambar Kantin">

                        <button class="kantin-btn">Kantin <?php echo $no++; ?></button>
                    </div>
                <?php 
                } 
                ?>
            </div>
        </div>

        <div class="parent" >
            <?php 
            $result_menu = mysqli_query($conn, "SELECT * FROM tb_menu");
            while ($row = mysqli_fetch_assoc($result_menu)): 
            ?>
                <div class="child">
                   <a href="detail_menu.php?id=<?php echo $row['ID_MENU']; ?>" class="menu-link">

                    <img src="/source/gambar_menu/<?php echo $row['FOTO_MENU']; ?>">

                    <h3><?php echo $row['NAMA_MENU']; ?></h3>

                    <div class="rating">★ 5.0</div>

                    <p class="harga">
                        Rp <?php echo number_format($row['HARGA'],0,',','.'); ?>
                    </p>

                </a>
                <form action="keranjang.php" method="POST">

                    <input type="hidden"
                    name="id_menu"
                    value="<?php echo $row['ID_MENU']; ?>">

                    <input type="hidden"
                    name="qty"
                    value="1">

                    <button type="submit"
                    name="add_to_cart"
                    class="add-btn">+</button>

                </form>

            </div>
            <?php endwhile; ?>
        </div>
        
</body>

</html>