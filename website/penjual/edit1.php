<?php
// =========================
// SESSION & KONEKSI
// =========================
session_start();
require_once '../include/koneksi.php';

// =========================
// PROTEKSI LOGIN
// =========================
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_login = $_SESSION['id_user'];

// =========================
// SEARCH
// =========================
$search = $_GET['search'] ?? '';

// =========================
// QUERY MENU
// =========================
$query_menu = "
SELECT m.* FROM tb_menu m
JOIN list_kantin k ON m.id_kantin = k.id
WHERE k.id_penjual = ?
";

if (!empty($search)) {
    $query_menu .= " AND m.NAMA_MENU LIKE ?";
}

$stmt = mysqli_prepare($conn, $query_menu);

if (!empty($search)) {
    $searchValue = "%$search%";
    mysqli_stmt_bind_param($stmt, "is", $id_login, $searchValue);
} else {
    mysqli_stmt_bind_param($stmt, "i", $id_login);
}

mysqli_stmt_execute($stmt);
$result_menu = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Saya</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #333;
            overflow-x: hidden;
        }

        /* Hide scrollbar */
        ::-webkit-scrollbar { width: 0; height: 0; }
        * { scrollbar-width: none; }

        .container {
            max-width: 1400px;
            margin: auto;
            padding: 20px;
            margin-top: 70px;
        }

        /* SEARCH */
        .search-box { margin-bottom: 20px; }
        .search-form { display: flex; gap: 12px; }
        .search-form input {
            flex: 1;
            padding: 14px 16px;
            border: none;
            border-radius: 14px;
            outline: none;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }
        .search-form button {
            padding: 14px 22px;
            border: none;
            border-radius: 14px;
            background: #F47B20;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }
        .search-form button:hover { background: #dd6b1d; }

        /* GRID */
        .parent {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        /* CARD */
        .child {
            background: white;
            border-radius: 20px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,.06);
            transition: .25s;
            display: flex;
            flex-direction: column;
        }
        .child:hover { transform: translateY(-5px); }
        .child img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 14px;
            margin-bottom: 12px;
        }
        .child h3 { font-size: 16px; margin-bottom: 6px; }
        
        .rating {
            color: #F47B20;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .harga {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        /* GROUP BUTTONS */
        .action-group {
            display: flex;
            gap: 8px;
            margin-top: auto;
            width: 100%;
        }

        .edit-btn, .review-btn {
            flex: 1;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
            cursor: pointer;
        }

        .edit-btn {
            border: 1.5px solid #F47B20;
            color: #F47B20;
            background: transparent;
        }
        .edit-btn:hover {
            background: #F47B20;
            color: white;
        }

        .review-btn {
            border: 1.5px solid #2563eb;
            color: #2563eb;
            background: transparent;
        }
        .review-btn:hover {
            background: #2563eb;
            color: white;
        }

        /* MODAL OVERLAY */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }
        .modal-overlay.active { display: flex; }
        
        .modal-box {
            width: min(500px, 92%);
            background: white;
            border-radius: 20px;
            padding: 24px;
            position: relative;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .modal-close {
            position: absolute;
            top: 12px;
            right: 16px;
            border: none;
            background: none;
            font-size: 26px;
            cursor: pointer;
            color: #666;
        }
        .modal-close:hover { color: #000; }

        .empty {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        @media(max-width:768px){
            .container { padding: 14px; }
            .parent { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .search-form { flex-direction: column; }
            .search-form button { width: 100%; }
            .action-group { flex-direction: column; gap: 6px; }
            .edit-btn, .review-btn { height: 38px; font-size: 12px; }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="../../source/icon/logo1.svg" alt="">
            </div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span><span></span><span></span>
            </label>
            <ul class="nav-links">
                <li><a href="penjual.php">Beranda</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="edit1.php" class="active">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="search-box">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Cari menu..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="parent">
            <?php if (mysqli_num_rows($result_menu) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_menu)): ?>
                    <div class="child">
                        <img src="../../source/gambar_menu/<?= htmlspecialchars($row['FOTO_MENU'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($row['NAMA_MENU']) ?>">
                        <h3><?= htmlspecialchars($row['NAMA_MENU']) ?></h3>
                        
                        <div class="rating">
                            <i class="fas fa-star"></i>
                           <?= $row['RATING'] ?? 'belum ada rating' ?>

                        </div>
                        <p class="harga">Rp <?= number_format($row['HARGA'], 0, ',', '.') ?></p>

                        <div class="action-group">
                            <a href="#" class="edit-btn js-edit-btn" data-id="<?= urlencode($row['ID_MENU']) ?>">
                                <i class="fas fa-edit"></i>&nbsp;Edit
                            </a>
                            <button class="review-btn js-review-btn" data-id="<?= urlencode($row['ID_MENU']) ?>">
                                <i class="fas fa-comments"></i>&nbsp;Ulasan
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty">Produk tidak ditemukan.</div>
            <?php endif; ?>
        </div>
    </div>

    <div id="editModal" class="modal-overlay">
        <div class="modal-box">
            <button class="modal-close" id="closeModal">&times;</button>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("editModal");
            const modalContent = document.getElementById("modalContent");
            const closeModal = document.getElementById("closeModal");

            const showLoading = () => {
                modalContent.innerHTML = `<div style="text-align:center; padding:40px;">Loading...</div>`;
                modal.classList.add("active");
                document.body.style.overflow = "hidden";
            };

            // 1. EVENT KLIK TOMBOL EDIT (MEMANGGIL editproduk.php)
            document.querySelectorAll(".js-edit-btn").forEach(btn => {
                btn.addEventListener("click", async (e) => {
                    e.preventDefault();
                    const id = btn.dataset.id;
                    try {
                        showLoading();
                        const res = await fetch(`editproduk.php?id=${id}`);
                        modalContent.innerHTML = await res.text();
                    } catch (err) {
                        modalContent.innerHTML = `<div style="text-align:center; padding:40px; color:red;">Gagal memuat data edit.</div>`;
                    }
                });
            });

            // 2. EVENT KLIK TOMBOL RATING/ULASAN (MEMANGGIL detail_ulasan.php BARU)
            document.querySelectorAll(".js-review-btn").forEach(btn => {
                btn.addEventListener("click", async (e) => {
                    e.preventDefault();
                    const id = btn.dataset.id;
                    try {
                        showLoading();
                        const res = await fetch(`detail_ulasan.php?id=${id}`);
                        modalContent.innerHTML = await res.text();
                    } catch (err) {
                        modalContent.innerHTML = `<div style="text-align:center; padding:40px; color:red;">Gagal memuat data ulasan.</div>`;
                    }
                });
            });

            // TUTUP MODAL FUNGSI
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
<<<<<<< HEAD
=======
    <script src="./../shared/js/script.js"></script>
    <!-- btn -->
    <script>
        // =================================================================
        // 1. DAFTARKAN FUNGSI SECARA GLOBAL (Di luar DOMContentLoaded)
        // Agar inline onclick="UpdateHarga()" di pop-up / modal bisa membaca fungsinya langsung
        // =================================================================
        
        window.UpdateHarga = function (step){
            const inputHARGA = document.getElementById("harga");
            if (!inputHARGA) return;

            let newValH = parseInt(inputHARGA.value) + step;

            // Validasi kelipatan 500 dan minimal 500
            if(newValH >= 500 && newValH % 500 == 0){
                inputHARGA.value = newValH;
            } 
        }

        window.UpdateStock = function (step){
            const inputSTOCK = document.getElementById("stok");
            const inputSTATUS = document.getElementById("status");
            if (!inputSTOCK) return;

            let newValS = parseInt(inputSTOCK.value) + step;

            if (newValS >= 0) {
                inputSTOCK.value = newValS;
                
                // Update status otomatis
                if (inputSTATUS) {
                    inputSTATUS.value = (newValS <= 0) ? "habis" : "tersedia";
                }
            }
        }

        // =================================================================
        // 2. LOGIKA UNTUK INPUT MANUAL (Ketika user mengetik angka)
        // =================================================================
        document.addEventListener("DOMContentLoaded", () => {
            const inputSTOCK = document.getElementById("stok");
            const inputHARGA = document.getElementById("harga");
            const inputSTATUS = document.getElementById("status");
       
            if (!inputHARGA || !inputSTOCK || !inputSTATUS) return;

            const hargaAwal = parseInt(inputHARGA.value) || 500;

            function statusCek(StokSekarang){
                if(StokSekarang <= 0){
                    inputSTATUS.value = "habis";
                } else {
                    inputSTATUS.value = "tersedia";
                }
            }

            // Handler ketik manual untuk stok
            inputSTOCK.oninput = (e) => {
                let target = e.target;
                let Value = parseInt(target.value);

                if(target.value === "" || isNaN(Value) || Value < 0){
                    target.value = 0;
                    Value = 0;
                }

                statusCek(Value);
            }

            // Handler ketik manual untuk harga
            inputHARGA.oninput = (e) => {
                let target = e.target;
                let Value = parseInt(target.value);

                if(target.value === "" || isNaN(Value) || Value < 500){
                    target.value = 500;
                } else if(Value % 500 != 0){
                    // Jika tidak kelipatan 500, kembalikan ke harga awal database
                    target.value = hargaAwal;
                }
            }
        });
        
        // CATATAN: Semua kode duplikat di bawah yang bikin numpuk sudah dihapus bersih!
    </script>

>>>>>>> f1d528f7e174c0107addb05bfedf399d57eadc3b
</body>
</html>