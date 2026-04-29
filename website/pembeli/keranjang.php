<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            color: black;
            font-family: 'Poppins', sans-serif;

        }

        .container {
            margin: 50px 5px 0 5px;
        }

        .parent {
            background-color: #dac8b9;
            padding: 15px;
            border-radius: 10px;


        }

        .text {
            margin-left: 10px;
        }

        /* Mengatur baris header dan produk agar sama persis */
        .header-tabel,
        .produk {
            display: grid;
            /* 4 Kolom: kolom pertama lebih lebar (2fr), sisanya sama rata (1fr) */
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            gap: 10px;
            padding: 8px;
            overflow-x: hidden;
            overflow-x: auto;
        }

        /* Warna background */
        .header-tabel {
            background: #fff5eb;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .produk {

            border-bottom: 1px solid #492509;

        }


        /* Styling gambar */
        .div1 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .div1 img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
        }

        .btn {
            border: none;
            outline: none;
            font-size: 14px;
            height: 40px;
            border-radius: 5px;
            color: white;
            margin: 20px 0 15px;
            background-color: #F47B20;
            box-shadow: 0 2px 5px #492509;
        }

        .card {
            background-color: #fff5eb;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="top-nav">
        <nav class="menu">
            <a href="pembeli.php">
                <img src="../../source/website1/icon/home1.svg" alt=" home"> <span class="nav-teks">Beranda</span>
            </a>
            <a href="#">
                <img src="../../source/website1/icon/pesanan2.svg" alt=""><span class="nav-teks">Pesanan</span>
            </a>
            <a href="#">
                <img src="../../source/website1/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>
    </div>
    <div class="container">
        <h2 class="text">History Pesanan</h2>
        <div class="parent">
            <div class="header-tabel">
                <div>Produk</div>
                <div>Payment</div>
                <div>Total</div>
                <div>Status</div>
            </div>
            <div class="card">
                <div class="produk">
                    <div class="div1">
                        <img src="nasi-goreng.jpg">
                        <div>
                            <p>Nasi Goreng</p>
                            <small>Varian: Spesial</small>
                        </div>
                    </div>

                    <div>CASH</div>

                    <div>8.000</div>

                    <div>
                        <button class="btn">Terima Pesanan</button>
                    </div>
                </div>

                <div class="produk">
                    <div class="div1">
                        <img src="nasi-goreng.jpg">
                        <div>
                            <p>Nasi Goreng</p>
                            <small>Varian: Spesial</small>
                        </div>
                    </div>

                    <div>CASH</div>

                    <div>8.000</div>

                    <div>
                        <button class="btn">Terima Pesanan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>