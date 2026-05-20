<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Penjual - KantinKita</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            max-width: 1200px;
            margin: 0 auto;
        }

        /* --- HEADER SECTION --- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: #111;
        }

        .header-title p {
            font-size: 14px;
            color: #666;
            margin-top: 2px;
        }

        .status-toko {
            display: flex;
            align-items: center;
            background-color: #fbeee6;
            padding: 8px 16px;
            border-radius: 12px;
            border: 1px solid #f5dcd0;
        }

        .status-toko span {
            font-size: 14px;
            font-weight: 500;
            margin-right: 12px;
            color: #4a2711;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .switch input { opacity: 0; width: 0; height: 0; }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider { background-color: #e06313; }
        input:checked + .slider:before { transform: translateX(24px); }

        /* --- CARDS SUMMARY --- */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .card-summary {
            background: #ffffff;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #eaeaea;
        }

        .card-icon-trend {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-pendapatan { background-color: #fbeee6; color: #e06313; }
        .icon-pesanan { background-color: #eef2ff; color: #4f46e5; }
        .icon-terjual { background-color: #e0f2fe; color: #0284c7; }

        .trend-label { font-size: 13px; font-weight: 500; }
        .trend-up { color: #10b981; }
        .trend-stable { color: #0284c7; }

        .card-summary p { font-size: 14px; color: #666; }
        .card-summary h2 { font-size: 28px; font-weight: 700; color: #111; margin-top: 4px; }

        /* --- MAIN LAYOUT CONTENT (FULL WIDTH) --- */
        .main-layout {
            display: block; /* Mengubah grid layout utama menjadi block agar konten memenuhi lebar halaman */
            width: 100%;
        }

        .card-section {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #eaeaea;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h3 { font-size: 18px; font-weight: 600; color: #111; }
        .section-link { font-size: 13px; color: #e06313; text-decoration: none; font-weight: 500; }

        /* --- RIWAYAT TRANSAKSI DENGAN CSS GRID --- */
        .grid-table {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* Menggunakan 1fr untuk pembagian kolom yang lebih merata di layout full-width */
        .grid-row-header, .grid-row-data {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr 1fr 1fr;
            align-items: center;
            padding: 12px 8px;
        }

        .grid-row-header {
            border-bottom: 2px solid #f5f5f5;
            font-weight: 500;
            color: #666;
            font-size: 14px;
        }

        .grid-row-data {
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
            color: #333;
            padding: 16px 8px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            width: max-content;
        }

        .status-selesai { background-color: #d1fae5; color: #065f46; }
        .status-proses { background-color: #fef3c7; color: #92400e; }

        /* Responsif untuk Layar Kecil (Mobile) */
        @media (max-width: 768px) {
            .summary-grid { grid-template-columns: 1fr; }
            .grid-row-header { display: none; } /* Sembunyikan header di mobile */
            .grid-row-data {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>

<div class="container">  <nav class="navbar">
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
                <li><a href="penjual.php"  class="active">Beranda</a></li>
                  <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="edit1.php">Produk</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <header class="header">
        <div class="header-title">
            <h1>Beranda Penjual</h1>
            <p>Rabu, 20 Mei 2026</p>
        </div>
        <div class="status-toko">
            <span>Status Toko: Buka</span>
            <label class="switch">
                <input type="checkbox" checked>
                <span class="slider"></span>
            </label>
        </div>
    </header>

    <section class="summary-grid">
        <div class="card-summary">
            <div class="card-icon-trend">
                <div class="icon-box icon-pendapatan">💵</div>
                <span class="trend-label trend-up">+12.5%</span>
            </div>
            <p>Pendapatan Hari Ini</p>
            <h2>Rp 1.250k</h2>
        </div>
 <div class="card-summary">
            <div class="card-icon-trend">
                <div class="icon-box icon-terjual">🛍️</div>
                <span class="trend-label trend-stable">Stable</span>
            </div>
            <p>Produk Terjual</p>
            <h2>82</h2>
        </div>
        <div class="card-summary">
            <div class="card-icon-trend">
                <div class="icon-box icon-pesanan">📄</div>
                <span class="trend-label trend-up" style="color:#4f46e5;"></span>
            </div>
            <p>Ratink</p>
            <h2>4.8/5</h2>
        </div>

       
    </section>

    <main class="main-layout">
        <div class="card-section">
            <div class="section-header">
                <h3>Riwayat Transaksi Mingguan</h3>
                <a href="#" class="section-link">Detail Laporan </a>
            </div>
            
            <div class="grid-table">
                <div class="grid-row-header">
                    <div>ID Transaksi</div>
                    <div>Menu</div>
                    <div>Jumlah</div>
                    <div>Total Harga</div>
                    <div>Status</div>
                </div>
                
                <div class="grid-row-data">
                    <div>#TRX-9821</div>
                    <strong>Ayam Geprek</strong>
                    <div>2 Porsi</div>
                    <div>Rp 30.000</div>
                    <div><span class="status-badge status-selesai">Selesai</span></div>
                </div>
                
                <div class="grid-row-data">
                    <div>#TRX-9820</div>
                    <strong>Nasi Goreng</strong>
                    <div>1 Porsi</div>
                    <div>Rp 15.000</div>
                    <div><span class="status-badge status-selesai">Selesai</span></div>
                </div>
                
                <div class="grid-row-data">
                    <div>#TRX-9819</div>
                    <strong>Es Teh Manis</strong>
                    <div>3 Gelas</div>
                    <div>Rp 12.000</div>
                    <div><span class="status-badge status-proses">Diproses</span></div>
                </div>

                <div class="grid-row-data">
                    <div>#TRX-9818</div>
                    <strong>Ayam Geprek</strong>
                    <div>1 Porsi</div>
                    <div>Rp 15.000</div>
                    <div><span class="status-badge status-selesai">Selesai</span></div>
                </div>
            </div>
        </div>
    </main>

</div>

</body>
</html>