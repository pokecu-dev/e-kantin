<?php

require_once __DIR__ . "/../include/koneksi.php";

if ($conn->error) {
    echo $conn->connect_error;
}

$sql = "SELECT * FROM users";
$query = $conn->query($sql);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    * {
        margin: 8px;
    }

    .header-tabel,
    .div1 {
        display: grid;
        /* 4 Kolom: kolom pertama lebih lebar (2fr), sisanya sama rata (1fr) */
        grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 1fr 1fr;
        gap: 10px;
        padding: 8px;
        min-width: 700px;
        border-bottom: 1px solid #492509;
       
        align-items: start;
    }

    .parent {
        background-color: #dac8b9;
        padding: 15px;
        border-radius: 10px;

    }

    .div1 {
        line-height: 1.4;
 max-height: fit-content;
    }

    .div1 p {
        word-break: break-word;
        margin: 0;
    }

    /* Warna background */
    .header-tabel {
        background: #fff5eb;
        font-weight: bold;
        border-radius: 5px;
        margin-bottom: 5px;
    }


    .card2 {
        overflow-x: auto;
    }
    p{
        font-size: small;
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

    /* .div1 {
            display: flex;
            align-items: center;
            gap: 10px; background-color: #492509;
            width: 100%;
              grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
        } */
</style>


<body>
    <header>
        <h1>
            Welcome to E-canteen EsemKita
        </h1>
    </header>
    <h2>BUAT TES TAMPIL DATA!</h2>

<div class="search-box">
        <h2>Cari User</h2>
        <!-- Data dikirim ke file hasil_user.php -->
        <form action="cariUser.php" method="GET">
            <input type="text" name="query" placeholder="Masukkan Username atau ID..." required>
            <button type="submit">Cari Sekarang</button>
        </form>
    </div>
    <main>
        
        <section>
            <a href="./login.php"><button class="btn">Login</button></a>
            <a href="./tambahmurid.php"><button class="btn">tambah murid</button></a>
            <a href="./addAdmin.php"><button class="btn">tambah admin</button></a>
            <a href="./addPenjual.php"><button class="btn">tambah penjual</button></a>
            <a href="./../logout.php"><button class="btn">log out</button></a>
        </section>



        <div class="parent">
            <div class="card2">
                <div class="header-tabel">
                    <p>ID</p>
                    <p>USERNAME</p>
                    <p>NAMA LENGKAP</p>
                    <p>NO TLP</p>
                    <p>EMAIL</p>
                    <p>ROLE</p>
                    <p>AKSI</p>
                </div>

                <?php while ($user = $query->fetch_assoc()): ?>

                    <div class="card">
                        <div class="card1">
                            <div class="div1">
                                <p><?= $user['ID'] ?></p>
                                <p><?= $user['USERNAME'] ?></p>
                                <p><?= $user['NAMA_LENGKAP'] ?></p>
                                <p><?= $user['NO_TLP'] ?></p>
                                <p><?= $user['EMAIL'] ?></p>
                                <p><?= $user['ROLE'] ?></p>
                                <p>
                                    <a href="edituser.php?id=<?= $user['ID'] ?>">edit</a>
                                </p>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

</body>

</html>