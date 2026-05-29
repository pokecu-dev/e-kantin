<?php
// session_start();

// echo ' sebagai pembeli';
require_once __DIR__ . "/../include/session/pembeliC.php";
require_once '../include/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                   font-family: 'Poppins', sans-serif ;
        
        }
        .nav-links a {
            text-decoration: none;
            color: #888;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a.active {
            color: var(--primary);
            border-bottom: 2px solid #F47B20;
            padding-bottom: 5px;
        }

        h1 {
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 1.2rem;
            line-height: 1.2;
            color: #F47B20;
        }

        .mencari {
            /* display: flex; */
             width: 100%;
             max-width:700px;
        }

        .search-box {
            width: 100%;
            margin: 20px auto;
            position: relative;
            padding: 0;
        }

        .search {
            width: 100%;
            padding: 12px 50px 12px 20px;
            border-radius: 30px;
            border: none;
            outline: none;
            display: flex;
            background-color: #ffffff;
            background-repeat: no-repeat;
            background-position: 15px center;
            /* Posisi ikon di kiri */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .iconsch {
            width: 24px;
            height: 24px;

        }

        .btn-search {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .container {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding-top: 100px;
        }

        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .menu-card {
            background-color: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .menu-card img {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            border-radius: 12px;
        }

        .nama {
            font-size: 14px;
            font-weight: 600;
            margin-top: 6px;
            color: #1A1A1A;
        }

        .rating {
            font-size: 14px;
            color: #F47B20;
            bold: 600;
        }

        .p {
            font-size: 14px;
            color: #1A1A1A;
            margin-bottom: 15px;
        }

        .slider {
            border-radius: 20px;
            margin-top: 30px;
            margin-bottom: 40px;
            width: 100%;

            overflow-x: hidden;

            position: relative;
        }

        .slides {
            display: flex;
            flex: 0 0 90%;
            gap: 22px;
            overflow-x: auto;
            padding: 10px 20px;
            scroll-snap-type: x mandatory;
            scroll-padding: 20px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            box-sizing: border-box;
            flex-wrap: nowrap;
        }

        .slide {
            flex: 0 0 85%;
            max-width: 480px;
            aspect-ratio: 21 / 10;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            scroll-snap-align: center;
            border-radius: 15px;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .kantin-btn {
            position: absolute;
            top: 80%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #F47B20;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            white-space: nowrap;
        }

        .slides::-webkit-scrollbar {
            display: none;
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

        @media (max-width: 1024px) {
            .parent {
                grid-template-columns: repeat(6, 1fr);
            }
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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
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
            font-size: 16px;
            font-weight: 600;
            margin: 4px 0;
            text-align: left;
            padding: 0 5px;
            color: #1A1A1A;
        }

        .child .rating {
            font-size: 13px;
            color: #F47B20;
            font-weight: 600;
            text-align: left;
            padding: 0 5px;
        }

        .child .harga {
            font-size: 14px;
            color: #1A1A1A;
            margin-top: 2px;
            margin-bottom: 5px;
            text-align: left;
            padding: 0 5px;
        }

        .menu-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .add-btn {
            text-decoration: none;
            display: flex;
            right: 10px;
            top: 138px;
            position: absolute;
            background: #F47B20;
            color: #ffffff;
            width: 32px;
            height: 32px;
            border-radius: 5px;
            z-index: 10;
            border: none;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.2s ease;
            justify-content: center;
            align-items: center;
            text-align: center;
            
        }

        .add-btn:hover {
            transform: scale(1.1);
            background: #F47B20;
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
                <li><a href="pembeli.php" class="active">Beranda</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="profil.php" >Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">

        <h1 class="teks">Cari Menu <b>Yang Kamu Inginkan!</b></h1>

        <div class="mencari">

            <form action="pembeli.php" method="GET">
                <div class="search-box">

                    <input
                        type="text"
                        name="search"
                        placeholder="Cari menu..."
                        class="search"
                        value="<?php echo $_GET['search'] ?? ''; ?>">

                    <button type="submit" class="btn-search">
                        <img src="../../source/icon/search.svg" class="iconsch">
                    </button>

                </div>
            </form>

        </div>




        <div class="slider">
            <div class="slides">
                <?php
                $result_kantin = $conn->query("SELECT * FROM list_kantin");
                $no = 1;
                while ($row = $result_kantin->fetch_assoc()) {
                    if ($row['STATUS'] == 1) {
                ?>
                        <div class="slide">
                            <img src="./../../source/foto_kantin/<?php echo $row['FOTO_KANTIN']; ?>" alt="Gambar Kantin">

                            <a href="kantin.php?id_kantin=<?php echo $row['ID']; ?>" class="co-btn kantin-btn">
                                Kantin <?php echo $row['ID']; ?>
                            </a>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="parent">
            <?php

            if (isset($_GET['search']) && $_GET['search'] != '') {

                $search = mysqli_real_escape_string($conn, $_GET['search']);

                $result_menu = mysqli_query(
                    $conn,
                    "SELECT m.* FROM tb_menu m
                        JOIN list_kantin k ON m.ID_KANTIN = k.ID
                        WHERE k.STATUS = 1
                        AND m.STATUS != 'nonaktif'
                        AND REPLACE(m.NAMA_MENU,' ','') LIKE '%" . str_replace(' ', '', $search) . "%'"
                );
            } else {

                $result_menu = mysqli_query(
                    $conn,
                    "SELECT m.* FROM tb_menu m
                        JOIN list_kantin k ON m.ID_KANTIN = k.ID
                        WHERE k.STATUS = 1
                        AND m.STATUS != 'nonaktif'"
                );
            }

            while ($row = mysqli_fetch_assoc($result_menu)):
                if ($row['STATUS'] !== "nonaktif"):
            ?>
                    <div class="child">
                        <a href="detail_menu.php?id=<?php echo $row['ID_MENU']; ?>" class="menu-link">

                            <img src="/source/gambar_menu/<?php echo $row['FOTO_MENU']; ?>">

                            <h3><?php echo $row['NAMA_MENU']; ?></h3>

                            <div class="rating">★ <?= $row['RATING'] ?? '0.0' ?></div>

                            <p class="harga">
                                Rp <?php echo number_format($row['HARGA'], 0, ',', '.'); ?>
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
        // document.getElementById('form-data').onsubmit = async (e) => {
        //     e.preventDefault();
        //     // const notif = document.getElementById('notif');
        //     const dataform = new FormData(e.target);

        //         try{
        //             const response = await fetch('keranjangDB.php',{
        //                 method:'POST',
        //                 body: dataform
        //             })
        //             // console.log(1);

        //             const data = await response.json();
        //             // console.log(2);
        //             alert(1);
        //             if(data.status === 'success'){
        //                 alert('hai')
        //                 // window.location.href = './keranjang.php'; 
        //             }
        //             console.log(data.message);

        //     }
        //     catch(e){
        //         alert("error : "+ e.message);
        //         // notif.innerText = "error:" + e.message;
        //     }

        // }
    </script>
</body>

</html>