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
            font-family: "Poppins", sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        /* ================= NAVBAR ================= */

        /* ================= HERO ================= */

        .hero {
            width: 100%;
            height: 250px;
            /* background:
                linear-gradient(rgba(0, 0, 0, 0.35),
                    rgba(0, 0, 0, 0.35)),
                url('https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1400&auto=format&fit=crop'); */
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
            font-size: 24px;
        }

        .username {
            color: #888;
            margin-top: 6px;
            font-size: 14px;
        }

        .badge {
            margin: 20px auto;
            background: #fff1e7;
            color: #F47B20;
            width: fit-content;
            padding: 10px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 25px;
        }

        .stat-box {
            background: #fafafa;
            padding: 15px 10px;
            border-radius: 18px;
        }

        .stat-box h3 {
            color: #F47B20;
            font-size: 20px;
        }

        .stat-box p {
            margin-top: 4px;
            font-size: 12px;
            color: #777;
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
            background: #ddffe8;
            color: #17a34a;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        /* ================= RESPONSIVE ================= */

        @media(max-width: 950px) {

            .container {
                flex-direction: column;
            }

            .profile-card {
                width: 100%;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .container {
                width: 100%;
                padding: 0 15px;
            }

            .detail-card {
                width: 100%;
            }

            .profile-card {
                width: 100%;
            }
        }

        @media(max-width: 650px) {


            .detail-card {
                padding: 25px;

            }

            .detail-header h2 {
                font-size: 22px;
            }

            .hero {
                height: 180px;
            }
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->

    <header class="topbar">

        <div class="logo-mobile">
            <img src="../../source/icon/logo1.svg" alt="KantinKita">
        </div>

        <div class="logo-desktop">
            <img src="../../source/icon/logo1.svg" alt="KantinKita">
        </div>
        <!-- --------/LOGO------------ -->
        <div class="top-nav" style="text-align: center; margin-bottom: 0px;">
            <nav class="menu">
                <a href="penjual.php" style="margin: 0 5px; text-decoration: none ; color:#F47B20">
                    <img src="../../source/icon/pesanan2.svg" alt="history">
                    <span>History</span>
                </a>
                <a href="edit1.php" class="active" style="margin: 0 5px; text-decoration: none;">
                    <img src="../../source/icon/edit1.svg" alt="edit">
                    <span style="color:#aaa;">Edit</span>
                </a>
                <div class="dropdown-container">
                    <a href="profil.php" style="margin: 0 5px; text-decoration: none; color:#F47B20">
                        <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks" style="color:#aaa;">Profile</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="profil.php" style="color: #202a39">Profile</a>
                        <a href="./../logout.php" style="color: #202a39">Keluar</a>
                    </div>
                </div>

            </nav>
        </div>

    </header>

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
            <p>@<?php echo $data['USERNAME']; ?></p>



        </div>

        <!-- DETAIL CARD -->

        <div class="detail-card">

            <div class="detail-header">
                <h2>Information Details</h2>

                <button class="edit-btn">
                    ✎ Edit Details
                </button>
            </div>

            <div class="detail-grid">

                <div class="input-group">
                    <label>Full Name</label>

                    <div class="input-box">
                        <p><?php echo $data['NAMA_LENGKAP']; ?></p>
                    </div>
                </div>


                <div class="input-group">
                    <label>Phone Number</label>

                    <div class="input-box">
                        <p><?php echo $data['NO_TLP']; ?></p>
                    </div>
                </div>

                <div class="input-group">
                    <label>Email Address</label>

                    <div class="input-box">
                        <?php echo $data['EMAIL']; ?>
                    </div>
                </div>

                <div class="input-group">
                    <label>Canteen Name</label>

                    <div class="input-box">
                        Kantin Bakso Bu Joko
                    </div>
                </div>


                <div class="input-group">
                    <label>Outlet Status</label>

                    <div class="status">
                        Aktif
                    </div>
                </div>
            </div>

        </div>

    </section>

</body>

</html>