<?php

session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    // echo $_SESSION['status'];
    header("location: ../index.php");
    exit();
}
if ($_SESSION['role'] != 'ADMIN') {
    header("location: ../index.php");
}



$nama = $_SESSION['nama_lengkap'];

// echo $nama . '<br> <br>';


// echo ' sebagai pembeli';


?>

<?php

require_once __DIR__ . "/../include/koneksi.php";

if ($conn->error) {
    echo $conn->connect_error;
}

$sql = "SELECT * FROM users";
$query = $conn->query("SELECT * FROM users ORDER BY ID DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canteen Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #F47B20;
            --bg: #f5f5f5;
            --white: #ffffff;
        }

        /* Warna default (Abu-abu) untuk semua menu */
        .nav-links a {
            text-decoration: none;
            color: #888;
            /* Warna abu-abu */
            font-weight: 500;
            transition: 0.3s;
        }

        /* Warna khusus (Merah) untuk menu yang sedang aktif */
        .nav-links a.active {
            color: var(--primary);
            /* Warna merah brand KantinKita */
            border-bottom: 2px solid #F47B20;
            /* Opsional: tambah garis bawah agar lebih jelas */
            padding-bottom: 5px;
        }



        body {
            background-color: var(--bg);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;

        }

        /* Stats Card Styling */
        .stats-container {
            margin-bottom: 30px;
            overflow: hidden;
            margin-top: 20px;
            padding: 0 20px;
        }

        .stats-wrapper {
            display: flex;
            gap: 15px;
            padding-bottom: 10px;
        }

        .stat-card {
            background: var(--white);
            padding: 20px;
            border-radius: 12px;
            min-width: 200px;
            flex: 1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        .stat-card p {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 10px 0 0 0;
            color: #111827;
        }

        /* User Table Card */
        .table-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            margin: 0 20px 0 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-view-all {
            text-decoration: none;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Indikator Dots */
        .dots {
            display: none;
            /* Sembunyi di desktop */
            justify-content: center;
            gap: 5px;
            margin-top: 10px;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #d1d5db;
            border-radius: 50%;
        }

        .dot.active {
            background: var(--primary);
        }

        /* Responsivitas Mobile */
        @media (max-width: 768px) {
            .stats-wrapper {
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
            }

            .stat-card {
                min-width: 80%;
                scroll-snap-align: center;
            }

            .dots {
                display: flex;
            }

            .stats-wrapper::-webkit-scrollbar {
                display: none;
            }
        }

        /* Styling untuk list user agar rapi */
        .header-tabel,
        .div1 {
            display: grid;
            grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 1fr 1fr;
            gap: 10px;
            padding: 8px;
            min-width: 700px;
            max-height: fit-content;
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

        }

        .div1 p {
            word-break: break-word;
        }

        /* Warna background */
        .header-tabel {
            background: #fff5eb;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .card1 {}

        .card2 {
            overflow-x: auto;
        }

        p {
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
    </style>
</head>

<body>

    <!-- Navigasi Utama -->
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
                <li><a href="admin.php" class="active">Beranda</a></li>
                <li><a href="akun.php">Akun</a></li>
                <li><a href="menu.php">Produk</a></li>
                <li><a href="oulet.php">Outlet</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <!-- Bagian Statistik (Card Horizontal) -->
    <div class="stats-container">
        <div class="stats-wrapper">
            <div class="stat-card">
                <h3>Active User</h3>
                <p>124</p>
            </div>
            <div class="stat-card">
                <h3>Jumlah Kantin</h3>
                <p>12</p>
            </div>
            <div class="stat-card">
                <h3>Jumlah Menu</h3>
                <p>85</p>
            </div>
            <div class="stat-card">
                <h3>Jumlah Penjual</h3>
                <p>10</p>
            </div>
        </div>
        <!-- Dot active hanya muncul di mobile via CSS -->
        <div class="dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <!-- Bagian Tabel Users -->
    <div class="table-card">
        <div class="table-header">
            <h2>Daftar User</h2>
            <a href="#" class="btn-view-all">Lihat Semua</a>
        </div>

        <div class="perent">
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
                <?php
                while ($user = $query->fetch_assoc()): ?>
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
                                    <a href="edituser.php?id=<?= $user['ID'] ?>" class="btn-edit">Edit</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <!-- <br>
    <p>tes up file</p>
    <form id="upfile-form">
        <label for="upfile">pilih file:</label>
        <input type="file" id="upfile" name="upfile">
        <button type="submit"">upload</button>
    </form> -->
    <!-- <div id=" notif" style="color: green;">hi</div> -->

            <!-- <br>
            <a href="TESTINGFITUR.php">tes WILAYAH TESTING FITUR >:[]</a>
            <a href="cariProduk.php">cari</a> -->


            <script>
                document.getElementById("upfile-form").onsubmit = async (events) => {

                    events.preventDefault();
                    const dataForm = new FormData(this);
                    const notif = document.getElementById("notif");

                    try {


                        const respon = await fetch('/../include/proses(universal)/upfile.php', {
                            method: "POST",
                            body: dataForm
                        });

                        const data = await respon.json();

                        notif.innerText = data.message;


                    } catch (error) {
                        console.error("Detail Error:", error);
                        notif.innerText = error.message;
                    }
                }
            </script>

</body>

</html>