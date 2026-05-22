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
    <title>Profile Kantin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ================= NAVBAR & HERO ================= */
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
            /* background-color: #F47B20;  */
        }

        /* ================= CONTENT ================= */
        .container {
            width: 92%;
            max-width: 1250px;
            margin: -80px auto 40px;
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
            font-size: 22px;
            font-weight: 700;
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
            font-size: 24px;
            font-weight: 700;
        }

        .edit-btn {
            border: none;
            background: #fff1e7;
            color: #F47B20;
            padding: 12px 20px;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
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

        .status {
            width: fit-content;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background: #ddffe8;
            color: #17a34a;
        }

        .status-inactive {
            background: #fee2e2;
            color: #ef4444;
        }

        .section-separator {
            margin-top: 20px;
        }

        /* ================= STYLING POP-UP MODAL ================= */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
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
            max-width: 550px;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-30px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-box {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #F1F5F9;
            padding-bottom: 15px;
        }

        .modal-header h3 {
            font-size: 20px;
            font-weight: 700;
        }

        .close-modal-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #94A3B8;
            cursor: pointer;
        }

        .close-modal-btn:hover {
            color: #EF4444;
        }

        /* Form dalam Modal */
        .modal-form-group {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .modal-form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
        }

        .modal-input {
            width: 100%;
            height: 50px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 0 16px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
        }

        .modal-input:focus {
            border-color: #F47B20;
            background: #FFF;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 25px;
            border-top: 1px solid #F1F5F9;
            padding-top: 15px;
        }

        .btn-cancel {
            background: #F1F5F9;
            color: #64748B;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-save {
            background: #F47B20;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #E06A12;
        }

        /* ================= RESPONSIVE ================= */
        @media(max-width: 950px) {
            .container {
                flex-direction: column;
                width: 100%;
                padding: 0 15px;
                margin-top: -60px;
            }

            .profile-card,
            .detail-card {
                width: 100%;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>
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

    <section class="hero"></section>

    <section class="container">
        <div class="profile-card">
            <div class="profile-image">
                <?php $foto_user = !empty($data['FOTO_USERS']) ? "../../source/fotopengguna/" . $data['FOTO_USERS'] : "../../source/fotopengguna/default.jpg"; ?>
                <img src="<?php echo $foto_user; ?>" class="profile-pic" alt="User Avatar">
            </div>
            <h2><?php echo htmlspecialchars($data['NAMA_LENGKAP']); ?></h2>
            <p class="username">@<?php echo htmlspecialchars($data['USERNAME']); ?></p>
        </div>

        <div class="detail-card">
            <div class="detail-header">
                <h2>Information Details</h2>
                <button class="edit-btn" onclick="toggleModal('modalUser')">✎ Edit Details</button>
            </div>
            <div class="detail-grid">
                <div class="input-group"><label>Full Name</label>
                    <div class="input-box">
                        <p><?php echo htmlspecialchars($data['NAMA_LENGKAP']); ?></p>
                    </div>
                </div>
                <div class="input-group"><label>Phone Number</label>
                    <div class="input-box">
                        <p><?php echo htmlspecialchars($data['NO_TLP']); ?></p>
                    </div>
                </div>
                <div class="input-group"><label>Email Address</label>
                    <div class="input-box">
                        <p><?php echo htmlspecialchars($data['EMAIL']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container section-separator">
        <div class="profile-card">
            <div class="profile-image" style="border-radius: 20px;">
                <img src="../../source/foto_kantin/<?= htmlspecialchars($row['FOTO_KANTIN'] ?? 'default.jpg') ?>">
            </div>
            <h2><?php echo htmlspecialchars($data['NAMA_KANTIN'] ?? 'Belum Ada Kantin'); ?></h2>
            <p class="username">Status Kepemilikan: Penjual</p>
        </div>

        <div class="detail-card">
            <div class="detail-header">
                <h2>Profil Kantin</h2>
                <button class="edit-btn" onclick="toggleModal('modalKantin')">✎ Edit Canteen</button>
            </div>
            <div class="detail-grid">
                <div class="input-group"><label>Canteen Name</label>
                    <div class="input-box">
                        <p><?php echo htmlspecialchars($data['NAMA_KANTIN'] ?? 'Belum Memiliki Kantin'); ?></p>
                    </div>
                </div>
                <div class="input-group">
                    <label>Outlet Status</label>
                    <div>
                        <?php if (isset($data['STATUS_KANTIN']) && $data['STATUS_KANTIN'] == 1): ?>
                            <div class="status status-active">Kantin Aktif (Buka)</div>
                        <?php else: ?>
                            <div class="status status-inactive">Kantin Nonaktif (Tutup)</div>
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
                    <input type="file" name="foto_user" class="modal-input" style="padding-top: 10px;">
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
                <input type="text" name="nama_kantin" class="modal-input" value="<?php echo htmlspecialchars($data['NAMA_KANTIN']); ?>" required>
            </div>
            
            <div class="modal-form-group">
                <label>Ganti Foto Kantin</label>
                <input type="file" name="foto_kantin" class="modal-input" style="padding-top: 10px;">
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

        // --- PROSES MENANGKAP NOTIFIKASI SWEETALERT2 ---
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const msg = urlParams.get('msg');

        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: msg || 'Data berhasil diperbarui.',
                confirmButtonColor: '#F47B20' // Warna oranye tema KantinKita
            }).then(() => {
                // Bersihkan URL dari parameter biar pas di-refresh ga muncul lagi
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