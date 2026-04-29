<?php
    /*
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] != 'success'){
        // echo $_SESSION['status'];
        header("location: ../login.php");
        exit();
    }





session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    // echo $_SESSION['status'];
    header("location: ../login.php");
    exit();
}


    echo ' sebagai pembeli';
*/

// $nama = $_SESSION['nama_lengkap'];

// echo $nama . '<br> <br>';


// echo ' sebagai pembeli';
?>


<?php 
require_once '../include/koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>KantinKita</title>
    <link rel="stylesheet" href="style_pembeli.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    
</head>

<body>
    <!-- <div style="padding: 10px;">
        <a href="../logout.php"><button>Log Out</button></a>
    </div> -->

    <div class="container">
        <div class="logo-mobile">
            <img src="../../source/icon/logo.svg" alt="KantinKita">
        </div>

        <h1 class="teks">Cari Menu <b>Yang Kamu Inginkan!</b></h1>

        <div class="mencari">

            <form action="search_menu.php" method="GET">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Cari menu..." class="search">
                    <button type="submit" class="btn-search">
                        <img src="../../source/icon/cari.svg" alt="" class="iconsch">
                        <img src="../source/icon" alt="" class="iconsch">
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
                   <img src="/source/gambar_menu/<?php echo $row['FOTO_MENU']; ?>">
                    <h3><?php echo $row['NAMA_MENU']; ?></h3>
                    <div class="rating">Rating 5,5 ★★★</div>
                    
                    <p>Rp <?php echo number_format($row['HARGA'], 0, ',', '.'); ?></p>
                    
                   <a href="order.php?id=<?php echo $row['ID_MENU']; ?>" class="add-btn">+</a>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="dots">
            <span class="dot dotactive"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
  


    <div class="logo-mobile">
        <img src="../../source/website1/icon/logo.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../../source/website1/icon/logo1.svg" alt="KantinKita">
    </div>


    <br>
    <br>
    <h2>tombol log out</h2>
    <!-- <a href="/logout.php"><button>log out</button></a> -->
    <a href="./../logout.php"><button>log out</button></a>


    <!-- <br>
    <p>tes up file</p>
    <form action="upfile.php" method="post" enctype="multipart/form-data">
        <label for="myfile">pilih file:</label>
        <input type="file" id="myfile" name="filename">
        <input type="submit" value="unggah">
    </form>

    <br>
    <a href="../TESTINGFITUR.php">tes WILAYAH TESTING FITUR >:[]</a>
     -->



    <div class="top-nav">
        <nav class="menu">
            <a href="#">
                <img src="../../source/website1/icon/home2.svg" alt=" home"> <span class="nav-teks">Beranda</span>
            </a>
            <a href="keranjang.php">
                <img src="../../source/website1/icon/pesanan1.svg" alt=""><span class="nav-teks">Pesanan</span>
            </a>
            <a href="#">
                <img src="../../source/website1/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>

    </div>
</body>

</html>