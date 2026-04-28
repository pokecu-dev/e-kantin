<?php




session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    // echo $_SESSION['status'];
    header("location: ../login.php");
    exit();
}

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

</head>

<body>

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
    <a href="../logout.php"><button>log out</button></a>

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