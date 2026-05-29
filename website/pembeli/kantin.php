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
    <style>
        .container {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding-top: 20px;
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
        }

        @media (max-width: 768px) {
            .parent {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .child {
            background: #ffffff;
            padding: 10px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .child:hover {
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
        }

        .menu-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .rating {
            font-size: 14px;
            color: #F47B20;
            font-weight: 600;
        }

        .add-btn {
            text-decoration: none;
            display: flex;
            right: 10px;
            bottom: 98px;
            position: absolute;
            background:  #F47B20;
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
            margin-bottom: 20px;
        }
        .banner-kantin img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .back-header-container {
            display: flex;
            align-items: center; 
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .back-header-container h1 {
            margin: 0;
            font-size: 1.5rem;
            line-height: 1.2;
            color: #F47B20;
        }
        .btn-back img {
            width: 24px; 
            display: block;
        }
    </style>
</head>
<body>

    <div class="banner-kantin">
        <img src="/source/foto_kantin/<?= $info_kantin['FOTO_KANTIN'] ?? 'banner_default.jpg'; ?>" alt="Banner Kantin" onerror="this.style.display='none'">
    </div>

    <div class="back-header-container">
        <a href="pembeli.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>
        <h1>Daftar Menu di <b><?= $info_kantin['NAMA_KANTIN'] ?? "Kantin ".$id_kantin; ?></b></h1>
    </div>
    

    <div class="container">

        <div class="parent">
            <?php
            $sql_menu = "SELECT m.*, AVG(r.RATING) as avg_rating 
                 FROM tb_menu m 
                 LEFT JOIN rating r ON m.ID_MENU = r.ID_MENU 
                 WHERE m.ID_KANTIN = $id_kantin 
                 GROUP BY m.ID_MENU";

            $result_menu = $conn->query($sql_menu);

            if ($result_menu && $result_menu->num_rows > 0) {
                while($row = $result_menu->fetch_assoc()):
                    if ($row['STATUS'] != 'nonaktif'):
            ?>
                        <div class="child">
                            <a href="detail_menu.php?id=<?php echo $row['ID_MENU'] ?? $row['id_menu']; ?>" class="menu-link">
                                <img src="/source/gambar_menu/<?php echo $row['FOTO_MENU'] ?? $row['foto_menu']; ?>">
                                <h3><?php echo $row['NAMA_MENU'] ?? $row['nama_menu']; ?></h3>
                                <div class="rating">★ <?php echo number_format($row['avg_rating'] ?? 0, 1); ?></div>
                                <p class="harga">
                                    Rp <?php echo number_format($row['HARGA'] ?? $row['harga'], 0, ',', '.'); ?>
                                </p>
                            </a>
                                
                            <form id="form-data" class="form-data">

                                <input type="hidden"
                                    name="id_menu"
                                    value="<?php echo $row['ID_MENU']; ?>">

                                <input type="hidden"
                                    name="qty"
                                    value="1">

                                <button type="submit"
                                    name="add_to_cart"
                                    class="add-btn">+</button>

                            </form>
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
                        alert('Berhasil menambahkan ke keranjang!');
                        // window.location.href = './keranjang.php'; 
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