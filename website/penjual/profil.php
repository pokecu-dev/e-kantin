<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    header("location: ../login.php");
    exit();
}

require_once __DIR__ . "/../include/koneksi.php";

$id_user = $_SESSION['id_user'];
$query_profile = mysqli_query($conn, "
    SELECT u.*, k.ID AS ID_KANTIN, k.NAMA_KANTIN, k.FOTO_KANTIN, k.STATUS AS STATUS_KANTIN 
    FROM users u 
    LEFT JOIN list_kantin k ON u.ID = k.id_penjual 
    WHERE u.ID = '$id_user'
");

$data = mysqli_fetch_array($query_profile);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Kantin - KantinKita</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
            font-family: 'Poppins', sans-serif;
            padding: 0;
            margin: 0;
        }

        /* ================= HERO BANNER POSITIONING SYSTEM ================= */
        .hero-banner {
            width: 100%;
            height: 280px;
            position: relative;
            margin-top: 40px;
            /* Menyelip di bawah navbar */
            margin-bottom: 70px;
          margin-bottom: 0;
        }

        .banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .banner-overlay {

            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.5) 100%);
        }

        /* Menaruh bungkus identitas tepat menempel menembus dasar bawah banner */
        .profile-header-wrapper {
            position: absolute;
         bottom: 40px;
            left: 4%;
            width: 92%;
            max-width: 1200px;
            z-index: 99;
        }

        .profile-identity {
            display: flex;
            align-items: flex-end;
            gap: 20px;
        }

        /* Kotak Avatar Bulat Sempurna */
        .user-avatar-box {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: white;
            flex-shrink: 0;
        }

        .user-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Mengatur posisi teks di samping kanan foto profil */
        .meta-titles {
            color: white;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.8);
            margin-bottom: 12px;
        }

        .meta-titles h1 {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
        }

        .meta-titles p {
            font-size: 14px;
            color: #ffffff;
            /* Mengubah warna abu-abu muda (#cbd5e1) menjadi putih bersih */
            font-weight: 500;
            /* Sedikit menebalkan huruf agar tidak terlalu tipis */
            margin-top: 4px;

            /* Menambahkan bayangan hitam yang menyebar rata ke segala arah (Glow Hitam Pekat) */
            text-shadow:
                -1px -1px 0 #000,
                1px -1px 0 #000,
                -1px 1px 0 #000,
                1px 1px 0 #000,
                0px 2px 6px rgba(0, 0, 0, 0.9);
        }

        /* ================= CONTAINER UTAMA ================= */
        .container {
            width: 92%;
            max-width: 1200px;
            margin: 0 auto 50px;
            position: relative;
            z-index: 10;
            margin: -30px auto 50px;
            z-index: 100;
        }

        /* GRID DETAIL MAJU */
        .profile-main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .detail-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(241, 245, 249, 0.8);
        }

        .card-title-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title-area h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title-area h2 i {
            color: #F47B20;
        }

        .edit-btn {
            border: none;
            background: #fff1e7;
            color: #F47B20;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .edit-btn:hover {
            background: #F47B20;
            color: white;
        }

        /* Baris Data Ringkas */
        .info-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 18px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-row label {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 14px;
            color: #334155;
            font-weight: 500;
        }

        /* Status Badge */
        .status-badge {
            width: fit-content;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-active {
            background: #dcfce7;
            color: #15803d;
        }

        .status-inactive {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* ================= MODAL OVERLAY STYLING ================= */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-box {
            background: white;
            width: 90%;
            max-width: 500px;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-box {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 14px;
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 700;
        }

        .close-modal-btn {
            background: #f1f5f9;
            border: none;
            font-size: 20px;
            color: #64748b;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .close-modal-btn:hover {
            background: #e2e8f0;
            color: #ef4444;
        }

        .modal-form-group {
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .modal-form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .modal-input {
            width: 100%;
            height: 46px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            font-family: inherit;
            transition: 0.2s;
        }

        .modal-input:focus {
            border-color: #F47B20;
            background: #fff;
        }

        /* ================= MODERN CUSTOM FILE UPLOAD BOX ================= */
        .file-upload-area {
            position: relative;
            width: 100%;
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-upload-area:hover {
            border-color: #F47B20;
            background: #fff7f2;
        }

        /* Menyembunyikan file input asli bawaan browser */
        .file-upload-area input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 28px;
            color: #94a3b8;
            margin-bottom: 8px;
            transition: color 0.2s;
        }

        .file-upload-area:hover .upload-icon {
            color: #F47B20;
        }

        .upload-text {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .upload-text span {
            color: #F47B20;
            font-weight: 600;
        }

        .file-name-preview {
            margin-top: 4px;
            font-size: 12px;
            color: #17a34a;
            font-weight: 600;
            display: none;
            /* Muncul lewat JS saat file dipilih */
        }

        /* Modal Footer */
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #64748b;
            border: none;
            padding: 10px 20px;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-save {
            background: #F47B20;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #db6a16;
        }

        /* ================= RESPONSIVE ================= */
       /* ================= MEDIA QUERY LAYAR KECIL / RESPONSIVE HP ================= */
        @media(max-width: 850px) {
            .hero-banner { 
                height: 200px; /* Sedikit memperpendek tinggi banner di HP */
                margin-bottom: 90px; /* Memberi ruang aman ke bawah karena teksnya turun */
            }

            /* Tarik pembungkus utama ke atas lebih ekstrem di layar HP */
            .profile-header-wrapper { 
                bottom: -75px; 
                left: 0;
                width: 100%;
                padding: 0 15px;
            }

            /* Ubah susunan dari kesamping jadi berbaris ke bawah */
            .profile-identity { 
                flex-direction: column; 
                gap: 8px; /* Mempersempit jarak antara foto dan teks */
                align-items: center; 
                text-align: center;
            }

            /* Kecilkan ukuran avatar bulat di HP agar tidak terlalu raksasa */
            .user-avatar-box {
                width: 100px;
                height: 100px;
                border-width: 3px;
            }

            /* Atur teks informasi agar naik dan warnanya solid gelap agar kontras dengan background putih */
            .meta-titles { 
                margin-bottom: 0;
                margin-top: 0;
            }

            .meta-titles h1 {
                font-size: 22px; /* Kecilkan ukuran font di HP */
                color: #1e293b;  /* UBAH JADI GELAP karena teks ini sudah turun ke area putih */
                text-shadow: none; /* Hapus shadow hitam banner */
            }

            .meta-titles p {
                font-size: 13px;
                color: #64748b;  /* UBAH JADI ABU-ABU GELAP agar terbaca jelas di area putih */
                text-shadow: none; 
                margin-top: 2px;
            }

            /* Tarik container grid putih agar tidak menabrak teks nama */
            .container {
                margin-top: 10px; /* Mengembalikan margin ke positif agar tidak balapan naik */
            }

            .profile-main-grid { 
                grid-template-columns: 1fr; 
                gap: 16px; 
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt="Logo"></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span><span></span><span></span>
            </label>
            <ul class="nav-links">
                <li><a href="penjual.php">Beranda</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php" class="active">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero-banner">
        <img src="../../source/foto_kantin/<?= htmlspecialchars($data['FOTO_KANTIN'] ?? 'default.jpg') ?>" class="banner-img" alt="Banner Kantin">
        <div class="banner-overlay"></div>

        <div class="profile-header-wrapper">
            <div class="profile-identity">
                <div class="user-avatar-box">
                    <?php $foto_user = !empty($data['FOTO_USERS']) ? "../../source/fotopengguna/" . $data['FOTO_USERS'] : "../../source/fotopengguna/default.jpg"; ?>
                    <img src="<?php echo $foto_user; ?>" alt="User Avatar">
                </div>
                <div class="meta-titles">
                    <h1><?php echo htmlspecialchars($data['NAMA_KANTIN'] ?? 'Belum Ada Kantin'); ?></h1>
                    <p><i class="fa-regular fa-user"></i> @<?php echo htmlspecialchars($data['USERNAME']); ?> &bull; Pemilik Outlet</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="profile-main-grid">

            <div class="detail-card">
                <div class="card-title-area">
                    <h2><i class="fa-solid fa-id-card"></i> Informasi Pemilik</h2>
                    <button class="edit-btn" onclick="toggleModal('modalUser')"><i class="fa-solid fa-pen-to-square"></i> Edit Profil</button>
                </div>

                <div class="info-row">
                    <label>Nama Lengkap</label>
                    <div class="info-value-box"><?php echo htmlspecialchars($data['NAMA_LENGKAP']); ?></div>
                </div>

                <div class="info-row">
                    <label>Nomor Telepon</label>
                    <div class="info-value-box"><?php echo htmlspecialchars($data['NO_TLP'] ?: '-'); ?></div>
                </div>

                <div class="info-row">
                    <label>Alamat Email</label>
                    <div class="info-value-box"><?php echo htmlspecialchars($data['EMAIL']); ?></div>
                </div>
            </div>

            <div class="detail-card">
                <div class="card-title-area">
                    <h2><i class="fa-solid fa-shop"></i> Detail Outlet</h2>
                    <button class="edit-btn" onclick="toggleModal('modalKantin')"><i class="fa-solid fa-store"></i> Edit Kantin</button>
                </div>

                <div class="info-row">
                    <label>Nama Kantin</label>
                    <div class="info-value-box"><?php echo htmlspecialchars($data['NAMA_KANTIN'] ?? 'Belum Memiliki Kantin'); ?></div>
                </div>

                <div class="info-row">
                    <label>Status Operasional</label>
                    <div>
                        <?php if (isset($data['STATUS_KANTIN']) && $data['STATUS_KANTIN'] == 1): ?>
                            <div class="status-badge status-active"><i class="fa-solid fa-circle-check"></i> Kantin Aktif</div>
                        <?php else: ?>
                            <div class="status-badge status-inactive"><i class="fa-solid fa-circle-xmark"></i> Kantin Nonaktif </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="modal-overlay" id="modalUser">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Edit Informasi Pengguna</h3>
                <button class="close-modal-btn" onclick="toggleModal('modalUser')">&times;</button>
            </div>
            <form action="process/update_user.php" method="POST" enctype="multipart/form-data">
                <div class="modal-form-group">
                    <label>Full Name</label>
                    <input type="text" name="nama_lengkap" class="modal-input" value="<?php echo htmlspecialchars($data['NAMA_LENGKAP']); ?>" required>
                </div>
                <div class="modal-form-group">
                    <label>Phone Number</label>
                    <input type="text" name="no_tlp" class="modal-input" value="<?php echo htmlspecialchars($data['NO_TLP']); ?>">
                </div>
                <div class="modal-form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="modal-input" value="<?php echo htmlspecialchars($data['EMAIL']); ?>" required>
                </div>

                <div class="modal-form-group">
                    <label>Ganti Foto Profil</label>
                    <div class="file-upload-area">
                        <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <div class="upload-text">Pilih foto atau <span>Klik di sini</span></div>
                        <input type="file" name="foto_user" onchange="previewFileName(this, 'userFilePreview')">
                        <div class="file-name-preview" id="userFilePreview"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="toggleModal('modalUser')">Batal</button>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modalKantin">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Edit Profil Kantin</h3>
                <button class="close-modal-btn" type="button" onclick="toggleModal('modalKantin')">&times;</button>
            </div>
            <form action="process/update_kantin.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_kantin" value="<?php echo $data['ID_KANTIN']; ?>">
                <div class="modal-form-group">
                    <label>Canteen Name</label>
                    <input type="text" name="nama_kantin" class="modal-input" value="<?php echo htmlspecialchars($data['NAMA_KANTIN'] ?? ''); ?>" required>
                </div>

                <div class="modal-form-group">
                    <label>Ganti Foto Kantin (Banner)</label>
                    <div class="file-upload-area">
                        <div class="upload-icon"><i class="fa-solid fa-images"></i></div>
                        <div class="upload-text">Pilih Banner baru atau <span>Klik di sini</span></div>
                        <input type="file" name="foto_kantin" union="banner" onchange="previewFileName(this, 'kantinFilePreview')">
                        <div class="file-name-preview" id="kantinFilePreview"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="toggleModal('modalKantin')">Batal</button>
                    <button type="submit" class="btn-save">Simpan Kantin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
            }
        }

        // Fungsi menampilkan nama file secara realtime saat di-upload
        function previewFileName(input, previewId) {
            const previewDiv = document.getElementById(previewId);
            if (input.files && input.files.length > 0) {
                previewDiv.innerText = "📄 Terpilih: " + input.files[0].name;
                previewDiv.style.display = "block";
            } else {
                previewDiv.style.display = "none";
            }
        }

        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const msg = urlParams.get('msg');

        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: msg || 'Data berhasil diperbarui.',
                confirmButtonColor: '#F47B20'
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: msg || 'Terjadi kesalahan sistem.',
                confirmButtonColor: '#EF4444'
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }
    </script>
</body>

</html>