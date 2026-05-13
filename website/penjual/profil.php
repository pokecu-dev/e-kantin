<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    header("location: ../login.php");
    exit();
}


require_once __DIR__ . "/../include/koneksi.php";
// 2. Ambil data menggunakan ID dari Session (Sangat Aman)
$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE ID = '$id_user'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - KantinKita</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange-main: #F47B20;
            --white: #FFFFFF;
            --gray-bg: #F0F2F5;
        }

        /* * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; width: 100%; }
        body { background-color: var(--gray-bg); color: #333; line-height: 1.6; } */
*{
    font-family: 'Poppins', sans-serif; 
}
        /* Bagian Atas: Dominan Oranye */
        .hero-profile {
            background: linear-gradient(135deg, var(--orange-main), #f4d120);
            height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            text-align: center;
        }

        .avatar-wrapper {
            position: relative;
            margin-bottom: 10px;
        }

        .profile-pic {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid var(--white);
            object-fit: cover;
            background-color: #eee;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        /* Bagian Konten: Dominan Putih */
        .main-container {
            max-width: 500px;
            margin: -40px auto 40px;
            padding: 0 15px;
        }

        .info-card {
            background: var(--white);
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .info-card h3 {
            color: var(--orange-main);
            margin-bottom: 25px;
            font-size: 1.2rem;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .detail-item {
            margin-bottom: 20px;
        }

        .detail-item label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .detail-item p {
            background: #F9FAFB;
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 0.95rem;
            color: #444;
            border-left: 3px solid var(--orange-main);
        }

        .btn-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-edit { background: var(--orange-main); color: white; }
        .btn-logout { background: #fee2e2; color: #ef4444; }

        .btn:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="logo-mobile">
        <img src="../../source/icon/logo.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../../source/icon/logo1.svg" alt="KantinKita">
    </div>
    <!-- --------/LOGO------------ -->
    <div class="top-nav" style="text-align: center; margin-bottom: 0px;">
        <nav class="menu">
            <a href="penjual.php" style="margin: 0 5px; text-decoration: none ; color:#F47B20">
                <img src="../../source/icon/pesanan2.svg" alt=""> 
                <span>History</span>
            </a>
            <a href="edit1.php" class="active" style="margin: 0 5px; text-decoration: none;">
                <img src="../../source/icon/edit1.svg" alt="">
                <span>Edit</span>
            </a>
            <div class="dropdown-container">
                <a href="profil.php" style="margin: 0 5px; text-decoration: none;">
                    <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks">Profile</span>
                </a>
                <div class="dropdown-content">
                    <a href="profil.php">Profile</a>
                    <a href="./../logout.php">Keluar</a>
                </div>
            </div>

        </nav>
    </div>

    <section class="hero-profile">
        <div class="avatar-wrapper">
            <?php 
                $foto = !empty($data['FOTO_USERS']) ? "../../source/fotopengguna/" . $data['FOTO_USERS'] : "../../source/fotopengguna/default.jpg";
            ?>
            <img src="<?php echo $foto; ?>" class="profile-pic" alt="User Avatar">
        </div>
        <h2><<?php echo $data['USERNAME']; ?></h2>
        <p style="font-size: 0.8rem; opacity: 0.9;">Role: <?php echo strtoupper($data['ROLE']); ?></p>
    </section>

    <div class="main-container">
        <div class="info-card">
            <h3>Informasi Akun</h3>

            <div class="detail-item">
                <label>Nama Lengkap</label>
                <p><?php echo $data['NAMA_LENGKAP']; ?></p>
                
            </div>

            <div class="detail-item">
                <label>Alamat Email</label>
                <p><?php echo $data['EMAIL']; ?></p>
            </div>

            <div class="detail-item">
                <label>Nomor WhatsApp</label>
                <p><?php echo $data['NO_TLP']; ?></p>
            </div>

            <div class="btn-group">
                <a href="edit1.php" class="btn btn-edit">Edit Profil</a>
                <a href="../logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
    </div>

</body>
</html>