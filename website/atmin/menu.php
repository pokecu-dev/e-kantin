<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Produk - CEO Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #f36f21;
            --bg-gray: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --white: #ffffff;
            /* Definisi Lebar Kolom agar sejajar */
            --col-product: 2fr;
            --col-category: 1fr;
            --col-price: 1.2fr;
            --col-stock: 1.2fr;
            --col-status: 0.8fr;
            --col-action: 0.5fr;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-gray);
            color: var(--text-dark);
            /* padding: 40px; */
            line-height: 1.5;
        }

        .nav-links a {
            text-decoration: none;
            color: #888;
            /* Warna abu-abu */
            font-weight: 500;
            transition: 0.3s;
        }

        /* Warna khusus (Merah) untuk menu yang sedang aktif */
        .nav-links a.active {
            color: var(--primary);
            /* Warna merah brand KantinKita */
            border-bottom: 2px solid #F47B20;
            /* Opsional: tambah garis bawah agar lebih jelas */
            padding-bottom: 5px;
        }

        .container {
            padding: 40px;
            margin: 0;
        }

        /* Header Section */
        .header {
            display: flex;
            /* justify-content: space-between;
            align-items: flex-start; */
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        /* .header-title p {
            color: var(--text-muted);
            font-size: 14px;
        } */

        .btn-add {
            background-color: var(--primary-orange);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-total {
            background: #fff7ed;
            color: #f97316;
        }

        .icon-low {
            background: #fef2f2;
            color: #ef4444;
        }

        .icon-active {
            background: #f0fdf4;
            color: #22c55e;
        }

        .stat-info span {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
            display: block;
        }

        .stat-info h2 {
            font-size: 24px;
            font-weight: 700;
        }

        /* Main List Section (Modern Grid Replacement for Table) */
        .data-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .toolbar {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .search-input {
            width: 320px;
            padding: 10px 15px 10px 35px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: #f1f5f9;
            outline: none;
        }

        /* Grid Layout */
        .grid-header,
        .grid-row {
            display: grid;
            grid-template-columns: var(--col-product) var(--col-category) var(--col-price) var(--col-stock) var(--col-status) var(--col-action);
            padding: 16px 20px;
            align-items: center;
        }

        .grid-header {
            background-color: #fafafa;
            border-bottom: 1px solid var(--border-color);
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .grid-row {
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
            transition: background 0.2s;
        }

        .grid-row:hover {
            background-color: #f8fafc;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .img-placeholder {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 20px;
        }

        /* Badges */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .badge-orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .badge-gray {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Custom Switch */
        .switch {
            width: 40px;
            height: 22px;
            position: relative;
            display: inline-block;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 20px;
        }

        .slider:before {
            content: "";
            position: absolute;
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: var(--primary-orange);
        }

        input:checked+.slider:before {
            transform: translateX(18px);
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            color: #94a3b8;
            cursor: pointer;
        }

        .footer {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--text-muted);
        }

        .pagination {
            display: flex;
            gap: 8px;
        }

        .btn-page {
            padding: 6px 16px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            background: #fff3e0;
            padding: 15px;
            width: 100%;
            border-radius: 8px;
        }

        input[type="text"] {
            flex: 1; padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-orange {
            background: #e67e22; color: white;
            border: none; padding: 10px 20px;
            border-radius: 5px; cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar" style=" font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
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
                <li><a href="admin.php">Beranda</a></li>
                <li><a href="akun.php">Akun</a></li>
                <li><a href="menu.php" class="active">Menu</a></li>
                <li><a href="#">Outlet</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>


    <div class="container">
        <div class="header">
            <div class="header-title">
                <form action="cariProduk.php" method="GET" class="input-group">
                    <input type="text" name="query" placeholder="Cari Nama, ID Menu, atau ID Kantin..." value="" required>
                    <button type="submit" class="btn-orange">Cari Menu</button>
                </form>
                <p>Pantau dan kelola inventaris produk secara real-time.</p>
            </div>
            <!-- <button class="btn-add"><span>+</span> Cari Produk</button> -->
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-box icon-total">📦</div>
                <div class="stat-info">
                    <span>TOTAL PRODUK</span>
                    <h2>1,284</h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box icon-low">⚠️</div>
                <div class="stat-info">
                    <span>STOK RENDAH</span>
                    <h2>12</h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-box icon-active">✅</div>
                <div class="stat-info">
                    <span>PRODUK AKTIF</span>
                    <h2>1,240</h2>
                </div>
            </div>
        </div>

        <div class="data-card">
            <div class="toolbar">
                <input type="text" class="search-input" placeholder="Cari nama produk atau kategori...">
                <div style="display: flex; gap: 10px;">
                    <button class="btn-page">📊 Filter</button>
                    <button class="btn-page">📥 Ekspor</button>
                </div>
            </div>

            <div class="grid-header">
                <div>Produk</div>
                <div>Kategori</div>
                <div>Harga</div>
                <div>Stok</div>
                <div>Status</div>
                <div style="text-align: right;">Aksi</div>
            </div>

            <div class="grid-row">
                <div class="product-info">
                    <div class="img-placeholder">📄</div>
                    <strong>Kursi Ergonomis Pro</strong>
                </div>
                <div>Furniture</div>
                <div>Rp 2.450.000</div>
                <div><span class="badge badge-green">45 Tersedia</span></div>
                <div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="actions">📝 🗑️</div>
            </div>

            <div class="grid-row">
                <div class="product-info">
                    <div class="img-placeholder">📄</div>
                    <strong>Meja Kerja Minimalis</strong>
                </div>
                <div>Furniture</div>
                <div>Rp 1.800.000</div>
                <div><span class="badge badge-orange">5 Sisa</span></div>
                <div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="actions">📝 🗑️</div>
            </div>

            <div class="grid-row">
                <div class="product-info">
                    <div class="img-placeholder">📄</div>
                    <strong>Lampu Meja LED</strong>
                </div>
                <div>Elektronik</div>
                <div>Rp 450.000</div>
                <div><span class="badge badge-gray">0 Stok</span></div>
                <div>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="actions">📝 🗑️</div>
            </div>

            <div class="footer">
                <div>Menampilkan 3 dari 1,284 produk</div>
                <div class="pagination">
                    <button class="btn-page">Sebelumnya</button>
                    <button class="btn-page" style="background: #f1f5f9; font-weight: 600;">Berikutnya</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>