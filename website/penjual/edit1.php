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

        /* Chrome, Edge, Safari */
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        /* Firefox */
        * {
            scrollbar-width: none;
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

        .container {

            margin: 40px 0 0 0;
        }

        /* --- Category Section --- */
        /* =========================
   CATEGORY
========================= */

        .kategori {
            display: flex;
            width: 100%;
            /* Gunakan 100% saja, hilangkan 100vw ganda */
            gap: 12px;
            max-width: 600px;
            padding: 10px 16px 18px;
            /* Ditambah padding kanan-kiri biar pas di-scroll gak mepet layar */
            overflow-x: auto;
            scrollbar-width: none;
        }

        .kategori::-webkit-scrollbar {
            display: none;
        }

        /* =========================
   BUTTON
========================= */
        .kat-btn {
            display: flex;
            align-items: center;
            /* Gunakan clamp untuk gap: min 6px, ideal 1vw, max 10px */
            gap: clamp(6px, 1vw, 10px);
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            cursor: pointer;
            flex: 0 0 auto;
            transition: 0.25s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);

            /* PENTING: Padding dinamis agar button membesar/mengecil proporsional */
            padding: clamp(8px, 1.2vw, 12px) clamp(12px, 1.8vw, 20px);
        }

        .kat-btn:hover {
            border-color: #F47B20;
            background: #fff7ed;
            transform: translateY(-2px);
        }

        /* =========================
   ICON (RESPONSIF & LEBIH GEDE)
========================= */
        .kat-btn img {
            /* - Di HP paling kecil (320px), ukurannya mulai dari 24px
       - Di layar sedang/gede, dia naik fleksibel mengikuti 6% lebar layar (6vw)
       - Di desktop, dia stop membesar di angka 36px biar gak over-size
    */
            width: clamp(45px, 6vw, 50px);
            height: clamp(45px, 6vw, 50px);

            object-fit: contain;
            flex-shrink: 0;
        }

        /* =========================
   TEXT (IKUT MENYESUAIKAN)
========================= */
        .kat-btn span {
            /* Teksnya juga kita buat fleksibel nemenin icon-nya */
            font-size: clamp(13px, 2.5vw, 16px);
            font-weight: 500;
            color: #1e293b;
            white-space: nowrap;
        }

        /* =========================
   ACTIVE
========================= */
        .kat-btn.active {
            background: #F47B20;
            border-color: #F47B20;
        }

        .kat-btn.active span {
            color: white;
        }

        /* =========================
   MOBILE
========================= */

        @media (max-width: 768px) {

            .kategori {
                gap: 10px;

                padding-bottom: 14px;
            }

            .kat-btn {
                padding: 8px 12px;

                border-radius: 14px;
            }

            .kat-btn img {
                width: 18px;
                height: 18px;
            }

            .kat-btn span {
                font-size: 12px;
            }
        }

        .parent {
            display: grid;
            grid-template-columns: repeat(auto-fit,
                    minmax(180px, 240px));
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

            .container {
                margin: 0px 0 0 0;
            }
        }


        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            width: min(450px, 92%);
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 12px;
            border: none;
            background: transparent;
            font-size: 26px;
            cursor: pointer;
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


    <!-- <div class="container">
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
    </div> -->
    <div class="container">
        <!-- Menu Grid -->
        <div class="parent">

            <?php
            // 4. Query Mengambil Produk Berdasarkan Kantin Milik Penjual yang Login
            // Kita gabungkan tb_menu (m) dan list_kantin (k) lewat ID Kantin yang sama
            $query_menu = "SELECT m.* FROM tb_menu m 
               JOIN list_kantin k ON m.id_kantin = k.id 
               WHERE k.id_penjual = ?";

            if ($stmt = mysqli_prepare($conn, $query_menu)) {
                // Ikat $id_login (dari session $_SESSION['id_user']) ke tanda tanya (?)
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
                            <a href="#" class="edit-btn js-edit-btn" data-id="<?= urlencode($row['ID_MENU']) ?>">
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
    </div>
    <!-- EDIT MODAL -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-box">
            <button class="modal-close" id="closeModal">&times;</button>
            <div id="modalContent">
                <!-- isi dari editproduk.php bakal masuk sini -->
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        const modal = document.getElementById("editModal");
        const modalContent = document.getElementById("modalContent");
        const closeModal = document.getElementById("closeModal");

        // open modal
        document.querySelectorAll(".js-edit-btn").forEach(btn => {
            btn.addEventListener("click", async (e) => {
                e.preventDefault();

                const id = btn.dataset.id;

                try {
                    const res = await fetch(`editproduk.php?id=${id}`);
                    const html = await res.text();

                    modalContent.innerHTML = html;
                    modal.classList.add("active");

                    document.body.style.overflow = "hidden";

                } catch (err) {
                    console.error("Gagal load modal:", err);
                }
            });
        });

        // close modal
        const close = () => {
            modal.classList.remove("active");
            modalContent.innerHTML = "";
            document.body.style.overflow = "";
        };

        closeModal.addEventListener("click", close);

        modal.addEventListener("click", (e) => {
            if (e.target === modal) close();
        });

    });
</script>

</html>