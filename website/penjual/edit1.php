<?php
require_once '../include/koneksi.php';
require_once __DIR__ . '/../include/session/penjualC.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_login = $_SESSION['id_user'];
$search = $_GET['search'] ?? '';

$query_menu = "
SELECT m.* FROM tb_menu m
JOIN list_kantin k ON m.id_kantin = k.id
WHERE k.id_penjual = ? AND m.STATUS != 'nonaktif'
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 0px !important;
            background: transparent !important;
        }

        html,
        body,
        *,
        div {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
            margin: auto;
            padding: 20px;
            margin-top: 90px;
        }

        .action-header-area {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 25px;
            width: 100%;
        }

        .search-box {
            flex: 1;
            position: relative;
        }

        .search-form {
            display: flex;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            z-index: 2;
        }

        .search-form input {
            flex: 1;
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            outline: none;
            background: white;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);
            font-size: 14px;
            transition: 0.3s;
        }

        .search-form input:focus {
            border-color: #F47B20;
            box-shadow: 0 0 0 4px rgba(244, 123, 32, 0.1);
        }

        .btn-orange-main {
            padding: 14px 24px;
            height: 48px;
            border: none;
            border-radius: 16px;
            background: #F47B20;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: .2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(244, 123, 32, 0.25);
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-orange-main:hover {
            background: #dd6b1d;
            transform: translateY(-1px);
        }

        .parent {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .child {
            background: white;
            border-radius: 24px;
            padding: 16px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.03);
            border: 1px solid rgba(241, 245, 249, 0.8);
            transition: .25s;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .child:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.08);
        }

        .image-wrapper {
            position: relative;
            width: 100%;

            margin-bottom: 14px;
        }

        .child img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 18px;
        }

        .stok-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            z-index: 10;
        }

        .stok-aman {
            background: #e6f7ed;
            color: #1f9254;
        }

        .stok-rendah {
            background: #fff8e6;
            color: #b7791f;
        }

        .stok-habis {
            background: #fde8e8;
            color: #9b1c1c;
        }

        .child h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .rating {
            color: #f59e0b;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rating-empty {
            color: #cbd5e1;
        }

        .harga {
            font-size: 18px;
            font-weight: 700;
            color: #F47B20;
            margin-bottom: 16px;
        }

        .action-group {
            display: flex;
            gap: 8px;
            margin-top: auto;
            width: 100%;
        }

        .edit-btn,
        .review-btn {
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
            background: white;
        }

        .edit-btn {
            border: 1.5px solid #204ef4;
            color: #2027f4;
        }

        .edit-btn:hover {
            background: #3220f4;
            color: white;
        }

        .review-btn {
            border: 1.5px solid #F47B20;
            color: #F47B20;
        }

        .review-btn:hover {
            background: #F47B20;
            color: white;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            padding: 16px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            width: min(540px, 100%);
            background: white;
            border-radius: 28px;
            padding: 30px;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
            border: 1px solid rgba(241, 245, 249, 0.8);
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            border: none;
            background: #f1f5f9;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .modal-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 18px;
        }

        .form-group-item.full-width {
            grid-column: span 2;
        }

        .form-group-item label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-style-input {
            height: 48px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
            color: #1e293b;
        }

        .form-style-input:focus {
            border-color: #F47B20;
            background: white;
            box-shadow: 0 0 0 4px rgba(244, 123, 32, 0.08);
        }

        .custom-upload-area {
            border: 2px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .custom-upload-area:hover {
            border-color: #F47B20;
            background: rgba(244, 123, 32, 0.01);
        }

        .custom-upload-area i {
            font-size: 24px;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .custom-upload-area p {
            font-size: 13px;
            color: #64748b;
        }

        .empty {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
            font-size: 15px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 14px;
                margin-top: 80px;
            }

            .action-header-area {
                display: flex !important;
                flex-direction: row !important;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                width: 100%;
            }

            .search-box {
                flex: 1 !important;
                width: auto !important;
            }

            .search-form {
                display: flex !important;
                width: 100% !important;
            }

            .search-form input {
                width: 100% !important;
                padding: 12px 14px 12px 38px;
                border-radius: 12px;
                font-size: 13px;
            }

            .btn-orange-main {
                flex: 0 0 auto !important;
                width: auto !important;
                max-width: 135px;
                padding: 0 14px;
                height: 44px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 600;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .btn-orange-main i {
                font-size: 13px !important;
                margin-right: 4px;
            }

            .parent {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .child {
                padding: 12px;
                border-radius: 18px;
            }

            .child h3 {
                font-size: 14px;
            }

            .harga {
                font-size: 15px;
                margin-bottom: 12px;
            }

            .action-group {
                flex-direction: column;
                gap: 6px;
            }

            .edit-btn,
            .review-btn {
                height: 36px;
                font-size: 12px;
            }

            .form-grid-two {
                grid-template-columns: 1fr;
            }

            .form-group-item.full-width {
                grid-column: span 1;
            }
        }
        .swal2-container {
            z-index: 999999 !important;
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
                <li><a href="pendapatan.php">Pendapatan</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="edit1.php" class="active">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="action-header-area">
            <div class="search-box">
                <form method="GET" class="search-form">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" placeholder="Cari menu harian..." value="<?= htmlspecialchars($search ?? '') ?>">
                </form>
            </div>
            <button class="btn-orange-main" id="js-add-product-trigger">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>

        <div class="parent">
            <?php if (mysqli_num_rows($result_menu) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_menu)): ?>
                    <?php
                    $stok = intval($row['STOK'] ?? 0);
                    if ($stok <= 0) {
                        $badgeClass = 'stok-habis';
                        $badgeText = 'Habis';
                    } elseif ($stok < 5) {
                        $badgeClass = 'stok-rendah';
                        $badgeText = 'Tersisa ' . $stok;
                    } else {
                        $badgeClass = 'stok-aman';
                        $badgeText = 'Tersedia ' . $stok;
                    }

                    $ratingValue = $row['RATING'];
                    $isRated = ($ratingValue !== null && $ratingValue > 0);
                    ?>
                    <div class="child">
                        <div class="image-wrapper">
                            <span class="stok-badge <?= $badgeClass ?>"><?= $badgeText ?></span>
                            <img src="../../source/gambar_menu/<?= htmlspecialchars($row['FOTO_MENU'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($row['NAMA_MENU']) ?>">
                        </div>

                        <h3><?= htmlspecialchars($row['NAMA_MENU']) ?></h3>

                        <div class="rating <?= !$isRated ? 'rating-empty' : '' ?>">
                            <i class="fas fa-star"></i>
                            <span><?= $isRated ? number_format($ratingValue, 1, '.', '') : '(0.0)' ?></span>
                        </div>

                        <p class="harga">Rp <?= number_format($row['HARGA'], 0, ',', '.') ?></p>

                        <div class="action-group">
                            <a href="#" class="edit-btn js-edit-btn" data-id="<?= urlencode($row['ID_MENU']) ?>">
                                <i class="fas fa-edit"></i>&nbsp; Edit Produk
                            </a>
                            <button class="review-btn js-review-btn" data-id="<?= urlencode($row['ID_MENU']) ?>">
                                <i class="fas fa-comments"></i>&nbsp;Lihat Ulasan
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty">
                    <i class="fas fa-hamburger" style="font-size:32px; margin-bottom:10px; color:#cbd5e1;"></i>
                    <p>Produk tidak ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="editModal" class="modal-overlay">
        <div class="modal-box">
            <button class="modal-close" id="closeModal">&times;</button>
            <div id="modalContent"></div>
        </div>
    </div>

    <template id="add-product-form-template">
        <div class="modal-title">
            <i class="fas fa-folder-plus" style="color:#F47B20;"></i> Tambah Produk Baru
        </div>
        <form action="process/proses_tambah.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid-two">
                <div class="form-group-item full-width">
                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" class="form-style-input" placeholder="Contoh: Nasi Goreng Gila" required>
                </div>

                <div class="form-group-item full-width">
                    <label>Deskripsi Produk</label>
                    <textarea name="desk" class="form-style-input" style="height: 80px; padding-top: 10px;" placeholder="Ceritakan kelezatan produkmu..." required></textarea>
                </div>

                <div class="form-group-item">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" id="modal_tambah_harga" class="form-style-input" value="1000" min="500" required>
                </div>

                <div class="form-group-item">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" id="modal_tambah_stok" class="form-style-input" value="10" min="0" required>
                </div>

                <div class="form-group-item">
                    <label>Kategori</label>
                    <select name="kategori" class="form-style-input" required>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="snack">Camilan</option>
                    </select>
                </div>

                <div class="form-group-item">
                    <label>Status</label>
                    <select name="status" id="modal_tambah_status" class="form-style-input" required>
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>

                <div class="form-group-item full-width">
                    <label>Foto Menu</label>
                    <div class="custom-upload-area" onclick="document.getElementById('modal_tambah_file').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p id="upload-text-info">Klik untuk upload foto</p>
                        <input type="file" name="foto_menu" id="modal_tambah_file" style="display:none;" accept="image/*" required onchange="document.getElementById('upload-text-info').innerText = this.files[0].name">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-orange-main" style="width:100%; margin-top:10px; height:50px; justify-content:center;">
                Simpan Produk
            </button>
        </form>
    </template>
<script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("editModal");
            const modalContent = document.getElementById("modalContent");
            const closeModal = document.getElementById("closeModal");

            const showLoading = () => {
                modalContent.innerHTML = `<div style="text-align:center; padding:40px; color:#64748b;"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>`;
                modal.classList.add("active");
                document.body.style.overflow = "hidden";
            };

            const addTrigger = document.getElementById("js-add-product-trigger");
            if (addTrigger) {
                addTrigger.addEventListener("click", () => {
                    const template = document.getElementById("add-product-form-template");
                    modalContent.innerHTML = template.innerHTML;
                    modal.classList.add("active");
                    document.body.style.overflow = "hidden";
                    initTambahFormValidation();
                    initFormSubmitAjax(); // Inisialisasi AJAX untuk form Tambah Produk
                });
            }

document.querySelectorAll(".js-edit-btn").forEach(btn => {
            btn.addEventListener("click", async (e) => {
                e.preventDefault();
                const id = btn.dataset.id;
                try {
                    showLoading();
                    const res = await fetch(`editproduk.php?id=${id}`);
                    modalContent.innerHTML = await res.text();

                    // =========================================================
                    // UTAMA: INTERCEPT FORM EDIT BIAR PAKE SWEETALERT + AUTO REFRESH
                    // =========================================================
                    const formEdit = modalContent.querySelector("form");
                    if (formEdit) {
                        formEdit.addEventListener("submit", async (evt) => {
                            evt.preventDefault(); // Menghentikan reload bawaan form kuno

                            const formData = new FormData(formEdit);
                            formData.append("update", "1"); // Pastikan key 'update' dikirim ke editproduk.php

                            try {
                                const response = await fetch("editproduk.php?id=" + id, {
                                    method: "POST",
                                    body: formData
                                });

                                if (response.ok) {
                                    // Munculin popup SweetAlert yang beneran melayang di depan
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Data produk berhasil diperbarui.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload(); // Selesai notif langsung refresh otomatis halaman utama
                                    });
                                } else {
                                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                                }
                            } catch (err) {
                                Swal.fire('Error!', 'Gagal menghubungkan ke server.', 'error');
                            }
                        });
                    }
                    // =========================================================

                } catch (err) {
                    modalContent.innerHTML = `<div style="text-align:center; padding:40px; color:#ef4444;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data edit.</div>`;
                }
            });
        });

            document.querySelectorAll(".js-review-btn").forEach(btn => {
                btn.addEventListener("click", async (e) => {
                    e.preventDefault();
                    const id = btn.dataset.id;
                    try {
                        showLoading();
                        const res = await fetch(`detail_ulasan.php?id=${id}`);
                        modalContent.innerHTML = await res.text();
                    } catch (err) {
                        modalContent.innerHTML = `<div style="text-align:center; padding:40px; color:#ef4444;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data ulasan.</div>`;
                    }
                });
            });

            const close = () => {
                modal.classList.remove("active");
                modalContent.innerHTML = "";
                document.body.style.overflow = "";
            };

            closeModal.addEventListener("click", close);
            modal.addEventListener("click", (e) => {
                if (e.target === modal) close();
            });

            function initTambahFormValidation() {
                const inputSTOCK = document.getElementById("modal_tambah_stok");
                const inputHARGA = document.getElementById("modal_tambah_harga");
                const inputSTATUS = document.getElementById("modal_tambah_status");

                if (!inputHARGA || !inputSTOCK || !inputSTATUS) return;

                inputSTOCK.oninput = (e) => {
                    let val = parseInt(e.target.value);
                    if (e.target.value === "" || isNaN(val) || val < 0) {
                        e.target.value = 0;
                        val = 0;
                    }
                    inputSTATUS.value = (val <= 0) ? "habis" : "tersedia";
                };

                inputHARGA.onchange = (e) => {
                    let val = parseInt(e.target.value);
                    if (e.target.value === "" || isNaN(val) || val < 500) {
                        e.target.value = 500;
                    } else if (val % 500 !== 0) {
                        e.target.value = Math.round(val / 500) * 500;
                    }
                };
            }

            // ========================================================
            // FUNGSI UTAMA AJAX SUBMIT + SWEETALERT + AUTO REFRESH
            // ========================================================
            function initFormSubmitAjax() {
                // Cari form yang ada di dalam modalContent (bisa form edit atau form tambah)
                const form = modalContent.querySelector("form");
                if (!form) return;

                form.addEventListener("submit", async (e) => {
                    e.preventDefault(); // Mencegah pindah halaman manual/bawaan form HTML

                    const formData = new FormData(form);
                    // Jika file editproduk.php membutuhkan deteksi nama button seperti isset($_POST['update'])
                    // kita bantu sisipkan secara manual ke formData:
                    formData.append('update', '1'); 

                    try {
                        // Ambil atribut action dari form tag, kalau kosong kirim ke file asal
                        const targetUrl = form.getAttribute("action") || window.location.href;

                        const response = await fetch(targetUrl, {
                            method: "POST",
                            body: formData
                        });

                        // Cek status response dari server PHP
                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data produk berhasil diperbarui.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload(); // Otomatis refresh halaman utama biar data terupdate
                            });
                        } else {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error!', 'Terjadi kegagalan sistem koneksi.', 'error');
                    }
                });
            }
        });
    </script>

    <script src="./../shared/js/script.js"></script>

    <script>
        window.UpdateHarga = function(step) {
            const inputHARGA = document.getElementById("harga");
            if (!inputHARGA) return;
            let newValH = parseInt(inputHARGA.value) + step;
            if (newValH >= 500 && newValH % 500 == 0) {
                inputHARGA.value = newValH;
            }
        }

        window.UpdateStock = function(step) {
            const inputSTOCK = document.getElementById("stok");
            const inputSTATUS = document.getElementById("status");
            if (!inputSTOCK) return;
            let newValS = parseInt(inputSTOCK.value) + step;
            if (newValS >= 0) {
                inputSTOCK.value = newValS;
                if (inputSTATUS) {
                    inputSTATUS.value = (newValS <= 0) ? "habis" : "tersedia";
                }
            }
        }

        window.updateFileName = function(input) {
            const fileNameDisplay = document.getElementById('file-chosen');
            if (input.files.length > 0) {
                fileNameDisplay.textContent = input.files[0].name;
            } else {
                fileNameDisplay.textContent = 'Pilih Foto Menu... '
            }
        }
    </script>

        

</body>

</html>