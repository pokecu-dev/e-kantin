<?php
require_once '../include/koneksi.php';
require_once __DIR__ . "/../include/session/pembeliC.php";

$id_kantin = isset($_GET['id_kantin']) ? (int)$_GET['id_kantin'] : 0;

if ($id_kantin > 0) {
    $sql = "SELECT m.*, AVG(r.RATING) as avg_rating 
            FROM tb_menu m 
            LEFT JOIN rating r ON m.ID_MENU = r.ID_MENU 
            WHERE m.ID_KANTIN = $id_kantin 
            GROUP BY m.ID_MENU";
            
    $result_menu = $conn->query($sql);
}else{
    echo "kantin tidak valid.";
    exit;
}

$query_info = $conn->query("SELECT * FROM list_kantin WHERE ID = $id_kantin");
$info_kantin = $query_info->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kantin - <?= $info_kantin['nama_kantin'] ?? "Kantin ".$id_kantin; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
    <link rel="stylesheet" href="style.css">
    <style>
      .container {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 0;
        }

        h1 {
            font-size: 1.5rem;
            line-height: 1.2;
            color: #F47B20;
        }

        .parent {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(199px, 1fr)); 
            gap: 15px;
            width: 98%;
            padding: 0 10px;
            max-width: 1400px;
            justify-content: center; 
            margin-top: -50px;
            position: relative;
            z-index: 5;
        }

        @media (max-width: 768px) {
            .parent {
                grid-template-columns: repeat(2, 1fr);
                margin-top: -70px;
            }
        }

        .child {
            background: #ffffff;
            padding: 8px;
            padding-bottom: 15px;
            border-radius: 12px;
            text-align: left;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .child:not(.menu-habis):hover {
            transform: translateY(-5px); 
        }

        .child img {
            width: 100%; 
            height: 160px;          
            aspect-ratio: 1 / 1;   
            object-fit: cover;     
            border-radius: 15px;   
            margin-bottom: 10px;
        }

        .child h3 {
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0 5px 0;
            color: #1A1A1A;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 40px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 4px;
            margin-top: auto; 
        }

        .child .harga {
            font-size: 16px; 
            color: #F47B20;
            font-weight: 700;
        }

        .child.menu-habis .harga {
            color: #999999;
        }

        .child.menu-habis .image-container img {
            filter: grayscale(1) brightness(0.8);
        }

        .overlay-habis {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
        }

        .badge-habis {
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .child .status-menu {
            font-size: 11px;
            color: #777777;
            font-weight: 500;
            text-align: right;
        }

        .menu-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 160px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .image-container img {
            width: 100%; 
            height: 100%;          
            object-fit: cover;     
        }

        .child .rating-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #F47B20;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .rating {
            font-size: 14px;
            color: #F47B20;
            font-weight: 600;
        }

        .add-btn {
            text-decoration: none;
            display: flex;
            right: 0;
            top: 128px;
            position: absolute;
            background: #F47B20;
            color: #ffffff;
            width: 32px;
            height: 32px;
            border-radius: 5px;
            z-index:10;
            border: none;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.2s ease;
            justify-content: center;
            align-items: center;
            text-align: center;
            line-height: 39px;
        }

        .add-btn:hover {
            transform: scale(1.1);
            background: #F47B20;
        }

        .banner-kantin {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f0f0f0;
            margin-bottom: 40px;
            position: relative;
        }

        .banner-kantin img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .banner-kantin::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.6));
            z-index: 1;
        }

        .back-header-container {
            display: flex;
            align-items: center; 
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: absolute;
            bottom: 70px; 
            left: 0;
            right: 0;
            z-index: 2;
            gap: 15px;
        }
                
        .back-header-container h1 {
            margin: 0;
            font-size: 1.5rem;
            line-height: 1.2;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .back-header-container h1 b {
            color: #ffffff;
        }

        .btn-back img {
            width: 24px; 
            display: block;
            filter: brightness(0) invert(1);
        }

        .floating-cart {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #F47B20;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(244, 123, 32, 0.4);
            z-index: 999;
            text-decoration: none;
            transition: transform 0.3s ease, background 0.2s ease;
        }

        .floating-cart:hover {
            transform: scale(1.1);
            background: #e06a14;
        }

        .floating-cart i {
            font-size: 24px;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -2px;
            background: #e74c3c;
            color: white;
            font-size: 12px;
            font-weight: 700;
            min-width: 22px;
            height: 22px;
            padding: 0 5px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid white;
        }

        @media (max-width: 768px) {
            .banner-kantin {
                height: 150px;
                margin-bottom: 25px;
            }
            .back-header-container {
                bottom: 80px; 
            }
            .back-header-container h1 {
                font-size: 1.2rem;
            }
            .floating-cart {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
            }
            .floating-cart i {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <a href="keranjang.php" class="floating-cart" id="floatingCartBtn">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-badge" id="cartCount">0</span>
    </a>

  <div class="banner-kantin">
    <img src="/source/foto_kantin/<?= $info_kantin['FOTO_KANTIN'] ?? 'banner_default.jpg'; ?>" alt="Banner Kantin" onerror="this.style.display='none'">
    
    <div class="back-header-container">
        <a href="pembeli.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>
        <h1>Daftar Menu di <b><?= $info_kantin['NAMA_KANTIN'] ?? "Kantin ".$id_kantin; ?></b></h1>
    </div>
</div>
    

    <div class="container">

        <div class="parent">
            <?php
            if ($result_menu && $result_menu->num_rows > 0) {
                while($row = $result_menu->fetch_assoc()):
                    if ($row['STATUS'] != 'nonaktif'):
                        $is_habis = ((int)$row['STOK'] <= 0);
            ?>
                        <div class="child <?= $is_habis ? 'menu-habis' : '' ?>">
                        
                            <div class="image-container">
                                <img src="/source/gambar_menu/<?php echo $row['FOTO_MENU']; ?>" alt="Foto Menu">
                                
                                <div class="rating-badge">★ <?php echo number_format($row['avg_rating'] ?? 0, 1); ?></div>
                                
                                <?php if ($is_habis): ?>
                                    <div class="overlay-habis">
                                        <div class="badge-habis">Habis</div>
                                    </div>
                                <?php else: ?>
                                <form class="form-data" method="POST"> <input type="hidden" name="id_menu" value="<?php echo $row['ID_MENU']; ?>">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" name="add_to_cart" class="add-btn">+</button>
                                </form>
                                <?php endif; ?>
                            </div>

                            <a href="<?= $is_habis ? '#' : 'detail_menu.php?id='.$row['ID_MENU']; ?>" 
                                class="menu-link" 
                                <?= $is_habis ? 'style="pointer-events: none; cursor: default;"' : ''; ?>>
                                    <h3><?php echo $row['NAMA_MENU']; ?></h3>
                                    <p class="harga">Rp <?php echo number_format($row['HARGA'], 0, ',', '.'); ?></p>
                            </a>
                            
                        </div>
            <?php 
                    endif;
                endwhile; 
            } else {
                echo "<p style='grid-column: 1/-1; text-align: center; color: #888; margin-top: 50px;'>Belum ada menu yang tersedia di kantin ini.</p>";
            }
            ?>
        </div>
    </div>
        <script>
        // Variabel lokal untuk menampung jumlah item sementara di halaman ini
        let totalItemDiKeranjang = 0;

        document.querySelectorAll('.form-data').forEach(form => {
            form.onsubmit = async (e) => {
                e.preventDefault();
                const dataform = new FormData(e.target);

                try {
                    const response = await fetch('keranjangDB.php', {
                        method: 'POST',
                        body: dataform
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        // 1. Naikkan angka badge keranjang secara real-time
                        totalItemDiKeranjang += 1;
                        document.getElementById('cartCount').innerText = totalItemDiKeranjang;
                        
                        // 2. Beri alert sukses kecil / log agar user tahu
                        alert('Berhasil menambahkan ke keranjang!');
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                } catch (error) {
                    alert("Error: " + error.message);
                }
            }
        });
    </script>
</body>
</html>