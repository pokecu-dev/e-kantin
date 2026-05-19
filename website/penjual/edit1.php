<?php
// 1. Mulai session di baris paling pertama
session_start();

// 2. Load koneksi
require_once '../include/koneksi.php';

// 3. Proteksi Halaman: Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_login = $_SESSION['id_user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KantinKita - Dashboard Penjual</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        /* --- Base Styles --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {

            font-family: 'Poppins', sans-serif;

        }

        /* --- Layout Container --- */
        .container {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 40px auto 0;
        }

        .active {
            color: #F47B20;
        }

        /* --- Category Section --- */
        .kategori {
            display: flex;
            gap: 10px;
            padding: 10px 0;
            overflow-x: auto;
            /* Memungkinkan scroll jika kategori banyak */
            scrollbar-width: none;

        }

        .kategori::-webkit-scrollbar {
            display: none;
        }

        .kat-btn {
            padding: clamp(5px, 0.8vw, 10px) clamp(10px, 1.2vw, 16px);

            border-radius: 10px;

            display: inline-flex;
            align-items: center;
            gap: 6px;

            background: #fff;
            border: 1px solid #eee;

            flex: 0 0 auto;

            width: fit-content;
        }

        .kat-btn img {
            .kat-btn img {
                width: clamp(18px, 2vw, 28px);
                height: clamp(18px, 2vw, 28px);
            }

            object-fit: contain;
        }

        .kat-btn span {
            .kat-btn span {
                font-size: clamp(11px, 1vw, 14px);
            }

            font-weight: 500;

            white-space: nowrap;
        }

        .kat-btn:hover {
            border-color: #F47B20;
            background: #fff7ed;
        }

        /* --- Grid System (Product Cards) --- */
        .parent {
            display: grid;
            grid-template-columns: repeat(auto-fit,
                    minmax(220px, 280px));
            gap: 20px;
            padding: 20px;
            max-width: 1500px;

            box-sizing: border-box;
            max-width: 100%;
        }

        .child {
            background: #ffffff;
            padding: 15px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            width: 100%;

        }

        .child:hover {
            transform: translateY(-5px);
        }

        .child img {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 12px;
        }

        .child h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .rating {
            font-size: 13px;
            color: #F47B20;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .harga {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 15px;
            color: #1A1A1A;
        }

        .edit-btn {
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #F47B20;
            height: 40px;
            width: 100%;
            border-radius: 12px;
            border: 1.5px solid #F47B20;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            margin-top: auto;
            /* Menjaga tombol tetap di bawah */
        }

        .edit-btn:hover {
            background: #F47B20;
            color: #fff;
        }

        /* --- Responsive Queries --- */
        @media (max-width: 480px) {
            .parent {
                grid-template-columns: repeat(2, 1fr);
                /* Tetap 2 kolom di HP */
                gap: 12px;
                padding: 12px;
            }

            .child {
                padding: 10px;
            }

            .child img {
                height: auto;
                /* Mengikuti rasio aspect-ratio */
            }

            .child h3 {
                font-size: 14px;
            }

            .kategori {
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <!-- Logo Section -->
    <header>
        <div class="logo-mobile">
            <img src="../../source/icon/logo1.svg" alt="KantinKita">
        </div>

        <div class="logo-desktop">
            <img src="../../source/icon/logo1.svg" alt="KantinKita">
        </div>
    </header>

    <!-- Navigation -->
    <div class="top-nav" style="text-align: center; margin-bottom: 0px;">
        <nav class="menu">
            <a href="penjual.php" style="margin: 0 5px; text-decoration: none;">
                <img src="../../source/icon/pesanan2.svg" alt="">
                <span style="color:#aaa;">History</span>
            </a>
            <a href="edit1.php" class="active" style="margin: 0 5px; text-decoration: none; color:#F47B20">
                <img src="../../source/icon/edit1.svg" alt="">
                <span>Edit</span>
            </a>
            <div class="dropdown-container">
                <a href="profil.php" style="margin: 0 5px; text-decoration: none;">
                    <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks" style="color:#aaa;">Profile</span>
                </a>
                <div class="dropdown-content">
                    <a href="profil.php" style="color: #202a39">Profile</a>
                    <a href="./../logout.php" style="color: #202a39">Keluar</a>
                </div>
            </div>

        </nav>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="kategori">
            <button class="kat-btn">
                <img src="./../source/icon/makanan.svg" alt="Makanan">
                <span>Makanan</span>
            </button>
            <button class="kat-btn">
                <img src="./../source/icon/minuman.svg" alt="Minuman">
                <span>Minuman</span>
            </button>
            <button class="kat-btn">
                <img src="./../source/icon/snack.svg" alt="Camilan">
                <span>Camilan</span>
            </button>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="parent">
        <?php
        // 4. Query Data dengan Prepared Statement
        $query_menu = "SELECT m.* FROM tb_menu m 
                       JOIN penjual_kantin pk ON m.id_kantin = pk.id_kantin 
                       WHERE pk.id_user = ?";

        if ($stmt = mysqli_prepare($conn, $query_menu)) {
            mysqli_stmt_bind_param($stmt, "i", $id_login);
            mysqli_stmt_execute($stmt);
            $result_menu = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result_menu) > 0) {
                while ($row = mysqli_fetch_assoc($result_menu)) {
        ?>
                    <div class="child">
                        <img src="../../source/gambar_menu/<?= htmlspecialchars($row['FOTO_MENU'] ?? 'default.jpg') ?>"
                            alt="<?= htmlspecialchars($row['NAMA_MENU']) ?>">

                        <h3><?= htmlspecialchars($row['NAMA_MENU']) ?></h3>

                        <div style="color: #F47B20; font-size: 12px; margin: 5px 0;">
                            <i class="fas fa-star"></i> 5.0
                        </div>

                        <p class="harga">Rp <?= number_format($row['HARGA'], 0, ',', '.') ?></p>

                        <a href="editproduk.php?id=<?= urlencode($row['ID_MENU']) ?>" class="edit-btn">
                            <i class="fas fa-edit" style="margin-right: 5px;"></i> Edit
                        </a>
                    </div>
        <?php
                }
            } else {
                echo "<p style='grid-column: 1/-1; text-align: center; color: #999; padding: 50px;'>Belum ada produk di kantin kamu.</p>";
            }
            mysqli_stmt_close($stmt);
        }
        ?>
    </div>

</body>

</html>