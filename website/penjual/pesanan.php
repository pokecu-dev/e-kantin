<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            padding: 24px;
        }

        .container {
    margin: 90px auto 30px;
    padding: 0 20px;
    max-width: 1300px;
}

.parent {
    background: #ffffff;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    overflow-x: auto;
}

/* HEADER + ROW */
.header-tabel,
.produk {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    align-items: center;
    gap: 20px;
    padding: 16px 18px;
    min-width: 750px;
}

/* HEADER */
.header-tabel {
    background: #fff5eb;
    border-radius: 14px;
    font-weight: 600;
    color: #492509;
    margin-bottom: 10px;
}

/* CARD */
.card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #eee;
}

/* ROW */
.produk {
    border-bottom: 1px solid #f1f1f1;
}

.produk:last-child {
    border-bottom: none;
}

/* PRODUK KIRI */
.div1 {
    display: flex;
    align-items: center;
    gap: 14px;
}

/* FOTO */
.detail {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.detail img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 12px;
}

/* ANTREAN */
.antri {
    font-size: 11px;
    background: #fff5eb;
    color: #F47B20;
    padding: 3px 8px;
    border-radius: 20px;
    font-weight: 600;
}

/* NAMA */
.div1 p {
    font-weight: 600;
    color: #1e293b;
}

.div1 small {
    color: #64748b;
}

/* BUTTON */
.btn {
    border: none;
    outline: none;
    padding: 12px 18px;
    border-radius: 10px;
    background: #F47B20;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
    width: 100%;
}

.btn:hover {
    background: #d86412;
    transform: translateY(-2px);
}

/* MOBILE */
@media (max-width: 768px) {

    .header-tabel,
    .produk {
        min-width: 650px;
    }

    .container {
        padding: 0 12px;
    }

    .btn {
        font-size: 13px;
        padding: 10px;
    }
}

    </style>
</head>
<body>
     <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>

            <!-- Burger Menu (Mobile Only) -->
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <ul class="nav-links">
                <li><a href="penjual.php" >Beranda</a></li>
                  <li><a href="pesanan.php" class="active">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>
    <!------------------------- PESANAN -------------------------->
   
    <div class="container">
        <h2 class="text">Daftar Pesanan</h2>
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
                       <div class="detail">
                        <small class="antri">Antrean: 10</small>
                        <img src="nasi-goreng.jpg">
                       </div>
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
                       <div class="detail">
                        <small class="antri">Antrean: 10</small>
                        <img src="nasi-goreng.jpg">
                       </div>
                        
                        <div>
                           
                            <p>Nasi Goreng</p>
                            <!-- <small>Varian: Spesial</small> -->
                            <small style="color: #F47B20;">Detail</small>
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
    <br>
    <br>

   
</body>
</html>