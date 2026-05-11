<?php 
require_once '../include/koneksi.php'; 
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

        .active{
            color: #F47B20;
        }
        /* --- Category Section --- */
        .kategori {
            display: flex;
            gap: 10px;
            padding: 10px 0;
            overflow-x: auto; /* Memungkinkan scroll jika kategori banyak */
        }

        .kat-btn {
            display: flex;
            align-items: center;
            background-color: #fff;
            border: none;
            border-radius: 10px;
            padding: 5px 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: 0.3s;
        }

        .kat-btn img {
            width: 35px;
            height: auto;
            margin-right: 8px;
        }

        .kat-btn span {
            font-size: 12px;
            font-weight: 600;
        }

        /* --- Grid System (Product Cards) --- */
        .parent {
            display: grid;
         grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1500px;
            margin: 0 auto;
            box-sizing: border-box;
            max-width: 100%;
        }

        .child {
            background: #ffffff;
            padding: 15px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
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
            margin-top: auto; /* Menjaga tombol tetap di bawah */
        }

        .edit-btn:hover {
            background: #F47B20;
            color: #fff;
        }

        /* --- Responsive Queries --- */
        @media (max-width: 480px) {
            .parent {
                grid-template-columns: repeat(2, 1fr); /* Tetap 2 kolom di HP */
                gap: 12px;
                padding: 12px;
            }

            .child {
                padding: 10px;
            }

            .child img {
                height: auto; /* Mengikuti rasio aspect-ratio */
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
        <img src="../../source/website1/icon/logo1.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../../source/website1/icon/logo1.svg" alt="KantinKita">
    </div>
    </header>

    <!-- Navigation -->
    <div class="top-nav" style="text-align: center; margin-bottom: 0px;">
        <nav class="menu">
            <a href="penjual.php" style="margin: 0 5px; text-decoration: none;">
                <img src="../../source/website1/icon/pesanan2.svg" alt="" > 
                <span>History</span>
            </a>
            <a href="edit1.php" class="active" style="margin: 0 5px; text-decoration: none; color:#F47B20">
                <img src="../../source/website1/icon/edit1.svg" alt="" >
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

    <!-- Main Content -->
    <div class="container">
        <div class="kategori">
            <button class="kat-btn">
                <img src="./../source/website1/icon/makanan.svg" alt="Makanan"> 
                <span>Makanan</span>
            </button>
            <button class="kat-btn">
                <img src="./../source/website1/icon/minuman.svg" alt="Minuman"> 
                <span>Minuman</span>
            </button>
             <button class="kat-btn">
                <img src="./../source/website1/icon/snack.svg" alt="Camilan"> 
                <span>Camilan</span>
            </button>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="parent">
        <?php 
        $result_menu = mysqli_query($conn, "SELECT * FROM tb_menu");
        while ($row = mysqli_fetch_assoc($result_menu)): 
        ?>
            <div class="child">
                <img src="/source/gambar_menu/<?php echo $row['FOTO_MENU']; ?>" alt="<?php echo $row['NAMA_MENU']; ?>">
                <h3><?php echo $row['NAMA_MENU']; ?></h3>
                <div class="rating">5.0 ★★★★★</div>
                <p class="harga">Rp <?php echo number_format($row['HARGA'], 0, ',', '.'); ?></p>
                <a href="editproduk.php?id=<?php echo $row['ID_MENU']; ?>" class="edit-btn">Edit Produk</a>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>