<?php

require_once __DIR__ . "/../include/session/pembeliC.php";
require_once __DIR__ . "/../include/koneksi.php";

// 2. Ambil data menggunakan ID dari Session
$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE ID = '$id_user'");
$data = mysqli_fetch_array($query);

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
    <title>Profile Kantin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f5f5;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        /* ================= NAVBAR ================= */
        .nav-links a {
            text-decoration: none;
            color: #888;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a.active {
            color: #F47B20;
            border-bottom: 2px solid #F47B20;
            padding-bottom: 5px;
        }

        /* ================= HERO ================= */
        .hero {
            width: 100%;
            height: 250px;
            background-size: cover;
            background-position: center;
            margin-top: -100px;
            position: relative;
            z-index: -1;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        /* ================= CONTENT ================= */
        .container {
            width: 92%;
            max-width: 1250px;
            margin: 0px auto 40px;
            display: flex;
            gap: 25px;
            align-items: flex-start;
        }

        /* ================= PROFILE CARD ================= */
        .profile-card {
            width: 320px;
            background: white;
            border-radius: 28px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
        }

        .profile-image {
            width: 120px;
            height: 120px;
            margin: auto;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid #fff;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-card h2 {
            margin-top: 20px;
            font-size: 24px;
        }

        .username {
            color: #888;
            margin-top: 6px;
            font-size: 14px;
        }

        /* ================= DETAIL CARD ================= */
        .detail-card {
            flex: 1;
            background: white;
            border-radius: 28px;
            padding: 35px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .detail-header h2 {
            font-size: 28px;
        }

        .edit-btn {
            border: none;
            background: #fff1e7;
            color: #F47B20;
            padding: 12px 20px;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .edit-btn:hover {
            background: #F47B20;
            color: white;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .input-group label {
            font-size: 12px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-box {
            height: 58px;
            background: #fafafa;
            border: 1px solid #ececec;
            border-radius: 18px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            font-size: 15px;
            color: #444;
            font-weight: 500;
        }

        /* ================= MODAL EDIT DETAILS (ORANGE-WHITE) ================= */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-wrapper {
            background: white;
            width: 90%;
            max-width: 750px;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-wrapper {
            transform: translateY(0);
        }

        .modal-title-area {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .modal-left, .modal-right {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modal-left {
            border-right: 1px solid #f1f5f9;
            padding-right: 15px;
        }

        .form-control-edit {
            width: 100%;
            height: 52px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 0 15px;
            font-family: inherit;
            font-size: 14px;
            color: #334155;
            outline: none;
            transition: 0.2s;
        }

        .form-control-edit:focus {
            border-color: #F47B20;
            box-shadow: 0 0 0 3px rgba(244, 123, 32, 0.15);
        }

        /* Upload Photo Section Inside Modal */
        .photo-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 15px;
            background: #fdfaf7;
            padding: 20px;
            border-radius: 18px;
            border: 1px dashed #fcd34d;
        }

        .preview-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .upload-action-btns {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .btn-upload-file {
            background: #F47B20;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-upload-file:hover {
            background: #d96514;
        }

        .btn-remove-file {
            background: #fff;
            color: #64748b;
            border: 1px solid #cbd5e1;
            padding: 9px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-remove-file:hover {
            background: #f8fafc;
            color: #ef4444;
        }

        /* Footer Buttons */
        .modal-action-footer {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            border-top: 2px solid #f1f5f9;
            padding-top: 20px;
        }

        .btn-modal-cancel {
            background: #cbd5e1;
            color: #334155;
            border: none;
            padding: 12px 25px;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-modal-cancel:hover {
            background: #94a3b8;
        }

        .btn-modal-save {
            background: #F47B20;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(244, 123, 32, 0.2);
            transition: 0.2s;
        }

        .btn-modal-save:hover {
            background: #d96514;
        }

        /* ================= RESPONSIVE ================= */
        @media(max-width: 950px) {
            .container { flex-direction: column; }
            .profile-card { width: 100%; }
            .detail-grid { grid-template-columns: 1fr; }
            .container { width: 100%; padding: 0 15px; }
            .detail-card { width: 100%; }
            .modal-grid { grid-template-columns: 1fr; gap: 20px; }
            .modal-left { border-right: none; padding-right: 0; }
        }

        @media(max-width: 650px) {
            .detail-card { padding: 25px; }
            .detail-header h2 { font-size: 22px; }
            .hero { height: 180px; }
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
                <li><a href="pembeli.php">Beranda</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="profil.php" class="active">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="back">
        <a href="pembeli.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>
    </div>

    <!-- ================= HERO ================= -->
    <section class="hero"></section>

    <!-- ================= CONTENT ================= -->
    <section class="container">

        <!-- PROFILE CARD -->
        <div class="profile-card">
            <div class="profile-image">
                <?php
                $foto = !empty($data['FOTO_USERS']) ? "../../source/fotopengguna/" . $data['FOTO_USERS'] : "../../source/fotopengguna/default.jpg";
                ?>
                <img src="<?php echo $foto; ?>" class="profile-pic" alt="User Avatar">
            </div>
            <h2><?php echo $data['NAMA_LENGKAP']; ?></h2>
            <p class="username">@<?php echo $data['USERNAME']; ?></p>
        </div>

        <!-- DETAIL CARD -->
        <div class="detail-card">
            <div class="detail-header">
                <h2>Information Details</h2>
                <button class="edit-btn" id="openEditModal">
                    ✎ Edit Details
                </button>
            </div>

            <div class="detail-grid">
                <div class="input-group">
                    <label>NAMA LENGKAP</label>
                    <div class="input-box">
                        <p><?php echo $data['NAMA_LENGKAP']; ?></p>
                    </div>
                </div>

                <div class="input-group">
                    <label>NOMOR TELEPON</label>
                    <div class="input-box">
                        <p><?php echo $data['NO_TLP']; ?></p>
                    </div>
                </div>

                <div class="input-group">
                    <label>ALAMAT EMAIL</label>
                    <div class="input-box">
                        <p><?php echo $data['EMAIL']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= MODAL BOX EDIT DETAILS ================= -->
    <div class="modal-overlay" id="editModalOverlay">
        <div class="modal-wrapper">
            <div class="modal-title-area">
                <span>✎</span> EDIT DETAILS
            </div>
            
            <!-- Arahkan form action ke berkas proses update milikmu, misal: proses_edit.php -->
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-grid">
                    
                    <!-- Sisi Kiri: Form Input Data Utama -->
                    <div class="modal-left">
                        <div class="input-group">
                            <label>Full Name</label>
                            <input type="text" name="nama_lengkap" class="form-control-edit" value="<?php echo $data['NAMA_LENGKAP']; ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label>Phone Number</label>
                            <input type="text" name="no_tlp" class="form-control-edit" value="<?php echo $data['NO_TLP']; ?>" required>
                        </div>
                        
                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control-edit" value="<?php echo $data['EMAIL']; ?>" required>
                        </div>
                    </div>
                    
                    <!-- Sisi Kanan: Pengaturan Foto Profil -->
                    <div class="modal-right">
                        <label style="font-size: 12px; color: #999; font-weight: 600; text-transform: uppercase;">Change Profile Photo</label>
                        <div class="photo-upload-container">
                            <img src="<?php echo $foto; ?>" class="preview-avatar" id="avatarPreview" alt="Avatar Preview">
                            <div class="upload-action-btns">
                                <!-- Input file asli disembunyikan agar tampilan tombol tetap rapi -->
                                <input type="file" id="fileInput" name="foto_user" accept="image/*" style="display: none;">
                                <button type="button" class="btn-upload-file" onclick="document.getElementById('fileInput').click();">
                                    ⬆ Upload New Photo
                                </button>
                                <button type="button" class="btn-remove-file" id="removePhotoBtn">
                                    🗑 Remove Photo
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tombol Konfirmasi Aksi -->
                <div class="modal-action-footer">
                    <button type="button" class="btn-modal-cancel" id="closeEditModal">X CANCEL</button>
                    <button type="submit" name="update_profile" class="btn-modal-save">✓ SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= JAVASCRIPT LOGIC ================= -->
    <script>
        const openBtn = document.getElementById('openEditModal');
        const closeBtn = document.getElementById('closeEditModal');
        const modalOverlay = document.getElementById('editModalOverlay');
        const fileInput = document.getElementById('fileInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const removePhotoBtn = document.getElementById('removePhotoBtn');

        // Buka modal
        openBtn.addEventListener('click', () => {
            modalOverlay.classList.add('active');
        });

        // Tutup modal via tombol cancel
        closeBtn.addEventListener('click', () => {
            modalOverlay.classList.remove('active');
        });

        // Tutup modal jika area luar diklik
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove('active');
            }
        });

        // Preview gambar instan ketika pengguna memilih file baru
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Reset foto preview kembali ke default (Opsional)
        removePhotoBtn.addEventListener('click', () => {
            avatarPreview.src = "../../source/fotopengguna/default.jpg";
            fileInput.value = ""; // hapus antrian file
        });
    </script>
</body>

</html>