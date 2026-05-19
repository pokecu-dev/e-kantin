<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KantinKita - Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* RESET UTAMA & VARIABEL WARNA */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

:root {
    --primary-color: #FF6B00; /* Oranye khas sesuai mockup */
    --primary-light: #FFF0E5;
    --bg-global: #F8F9FA;
    --card-bg: #FFFFFF;
    --text-main: #1A1A1A;
    --text-muted: #7A7A7A;
    --green-trend: #10B981;
    --red-trend: #EF4444;
    --border-color: #ECECEC;
}

body {
    background-color: var(--bg-global);
    color: var(--text-main);
    padding-bottom: 50px;
}



#check {
    display: none;
}

.checkbtn {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
}

.checkbtn span {
    width: 25px;
    height: 3px;
    background-color: var(--text-main);
    border-radius: 2px;
}

/* ==========================================
   LAYOUT UTAMA DASHBOARD (Grid 2 Kolom)
   ========================================== */
.dashboard-wrapper {
    display: column;
    gap: 30px;
    max-width: 1400px;
    margin: 30px auto;
    padding: 0 30px;
}

/* KARTU GLOBAL */
.stat-card, .chart-card, .sidebar-card, .product-card {
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    padding: 24px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 20px;
    font-weight: 700;
}

/* LAPORAN STATISTIK */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 22px;
    font-weight: 700;
    margin: 8px 0;
}

.stat-trend {
    font-size: 12px;
    font-weight: 500;
}

.trend-up { color: var(--green-trend); }
.trend-down { color: var(--red-trend); }

/* SEKSI GRAFIK */
.chart-card {
    margin-bottom: 35px;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.chart-legend {
    display: flex;
    gap: 15px;
    font-size: 13px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.revenue-dot { background-color: var(--primary-color); }
.orders-dot { background-color: #2563EB; }

.chart-bars {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    height: 200px;
    padding-top: 20px;
    border-bottom: 1px solid var(--border-color);
}

.bar-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 10%;
    height: 100%;
    justify-content: flex-end;
}

.bar {
    width: 80%;
    border-radius: 4px 4px 0 0;
    transition: transform 0.3s ease;
}

/* Variasi Opacity Bar Oranye */
.bar-light { background-color: #FFE6D5; }
.bar-mid { background-color: #FFA366; }
.bar-dark { background-color: var(--primary-color); }

.bar-label {
    font-size: 10px;
    color: var(--text-muted);
    margin-top: 8px;
}

/* SEKSI PRODUK */
.view-toggle {
    display: flex;
    gap: 5px;
}

.toggle-btn {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
}

.toggle-btn.active {
    background: var(--primary-light);
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.product-card {
    padding: 0;
    overflow: hidden;
}

.product-img-wrapper {
    position: relative;
    width: 100%;
    height: 160px;
    background-color: #EEE;
}

.product-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}

.badge-available { background-color: #D1FAE5; color: #065F46; }
.badge-empty { background-color: #FEE2E2; color: #991B1B; }

.product-info {
    padding: 16px;
}

.product-info h3 {
    font-size: 15px;
    margin-bottom: 6px;
}

.product-price {
    color: var(--primary-color);
    font-weight: 700;
}

.btn-load-more {
    width: 100%;
    padding: 14px;
    background-color: #F3F4F6;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-load-more:hover {
    background-color: #E5E7EB;
}

/* ==========================================
   STYLE SIDEBAR (KOLOM KANAN)
   ========================================== */
.sidebar-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.seller-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.seller-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.seller-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #D1D5DB;
}

.seller-detail h4 {
    font-size: 14px;
    font-weight: 600;
}

.seller-detail p {
    font-size: 10px;
    color: var(--text-muted);
    font-weight: 700;
    margin-top: 2px;
}

.btn-link {
    margin-left: auto;
    font-size: 11px;
    font-weight: 700;
    color: var(--primary-color);
    text-decoration: none;
}

.divider {
    border: none;
    border-top: 1px solid var(--border-color);
    margin: 20px 0;
}

/* ITEM KONTAK */
.outlet-info h4, .info-kontak-card h3 {
    font-size: 11px;
    color: #9CA3AF;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
}

.info-kontak-card h3 {
    font-size: 16px;
    color: var(--text-main);
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px;
}

.info-icon {
    font-size: 16px;
}

.info-label {
    font-size: 9px;
    font-weight: 700;
    color: #9CA3AF;
}

.info-text {
    font-size: 13px;
    font-weight: 600;
    margin-top: 2px;
}

.info-subtext {
    font-size: 11px;
    color: var(--primary-color);
    margin-top: 2px;
}

/* TOMBOL TARGET BULANAN */
.target-button {
    background: linear-gradient(135deg, #FF6B00 0%, #FF8533 100%);
    color: white;
    border: none;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
}

.target-button h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 4px;
}

.target-button p {
    font-size: 12px;
    opacity: 0.9;
}



/* ==========================================
   STYLE UNTUK RIWAYAT TRANSAKSI OUTLET
   ========================================== */
.transaction-section {
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    padding: 24px;
    margin-bottom: 35px; /* Jarak ke seksi Daftar Produk di bawahnya */
}

.view-all-link {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-color);
    text-decoration: none;
    transition: opacity 0.2s;
}

.view-all-link:hover {
    opacity: 0.8;
}

/* Pembungkus tabel agar bisa di-scroll horizontal di mobile */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.transaction-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 14px;
}

/* Header Tabel */
.transaction-table th {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    letter-spacing: 0.5px;
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    text-transform: uppercase;
}

/* Baris Sel Data */
.transaction-table td {
    padding: 18px 16px;
    color: var(--text-main);
    border-bottom: 1px solid #F3F4F6;
    vertical-align: middle;
}

/* Efek hover baris agar interaktif */
.transaction-table tbody tr:hover {
    background-color: #FAFAFA;
}

/* Kolom khusus ID Transaksi */
.tx-id {
    font-weight: 700;
    color: #B25E29; /* Warna cokelat/oranye tua sesuai gambar */
}

/* Gaya Dasar Badge Status */
.status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
}

/* Variasi Warna Status */
.status-success {
    background-color: #E6F7ED;
    color: #22C55E;
}

.status-pending {
    background-color: #FDF2E9;
    color: #D97706;
}

.status-danger {
    background-color: #FEE2E2;
    color: #EF4444;
}
/* ==========================================
   RESPONSIVE DESIGN (MEDIA QUERIES)
   ========================================== */
@media (max-width: 1024px) {
    .dashboard-wrapper {
        grid-template-columns: 1fr; /* Kolom berubah jadi menumpuk vertikal */
    }
}

@media (max-width: 768px) {
    .stats-grid, .products-grid {
        grid-template-columns: 1fr; /* Responsif untuk layar hp */
    }
    
    /* Hambuger menu trigger logic */
    .checkbtn { display: flex; }
    .nav-links {
        position: absolute;
        width: 100%;
        height: 100vh;
        background: var(--card-bg);
        top: 70px;
        left: -100%;
        text-align: center;
        flex-direction: column;
        padding-top: 50px;
        transition: all .5s;
    }
    #check:checked ~ .nav-links {
        left: 0;
    }
}
    </style>
</head>
<body>

  
    <div class="dashboard-wrapper">
        
        <main class="main-content">
            
            <section class="report-section">
                <div class="section-header">
                    <h2>Laporan Hari Ini</h2>
                    <span class="report-date">📅 24 Okt 2023</span>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-label">TOTAL REVENUE</span>
                        <h3 class="stat-value">Rp 12.450.000</h3>
                        <span class="stat-trend trend-up">↑ +12.5% vs kemarin</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">TOTAL TRANSACTIONS</span>
                        <h3 class="stat-value">342</h3>
                        <span class="stat-trend trend-up">↑ +4.2% vs kemarin</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">AVG. ORDER VALUE</span>
                        <h3 class="stat-value">Rp 36.403</h3>
                        <span class="stat-trend trend-down">↓ -1.8% vs kemarin</span>
                    </div>
                </div>

              <div class="chart-card">
    <div class="chart-header">
        <h4>Daily Sales Trend</h4> 
        <div class="chart-legend">
            <span class="legend-item"><span class="dot revenue-dot"></span> Revenue</span>
            <span class="legend-item"><span class="dot orders-dot"></span> Orders</span>
        </div>
    </div>
    <div class="chart-bars">
        <div class="bar-container">
            <div class="bar bar-light" style="height: 45%;"></div>
            <span class="bar-label">Sen</span>
        </div>
        <div class="bar-container">
            <div class="bar bar-mid" style="height: 60%;"></div>
            <span class="bar-label">Sel</span>
        </div>
        <div class="bar-container">
            <div class="bar bar-dark" style="height: 75%;"></div>
            <span class="bar-label">Rab</span>
        </div>
        <div class="bar-container">
            <div class="bar bar-mid" style="height: 50%;"></div>
            <span class="bar-label">Kam</span>
        </div>
        <div class="bar-container">
            <div class="bar bar-dark" style="height: 90%;"></div>
            <span class="bar-label">Jum</span>
        </div>
        <div class="bar-container">
            <div class="bar bar-light" style="height: 35%;"></div>
            <span class="bar-label">Sab</span>
        </div>
        <div class="bar-container">
            <div class="bar bar-dark" style="height: 80%;"></div>
            <span class="bar-label">Ming</span>
        </div>
    </div>
</div>
            </section>


            <section class="transaction-section">
    <div class="section-header">
        <h2>Riwayat Transaksi Outlet</h2>
        <a href="#" class="view-all-link">Lihat Semua →</a>
    </div>

    <div class="table-responsive">
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>WAKTU</th>
                    <th>SISWA</th>
                    <th>MENU</th>
                    <th>TOTAL</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tx-id">#TRX-9942</td>
                    <td>12:45</td>
                    <td>Andi Wijaya</td>
                    <td>Nasi Goreng Spesial</td>
                    <td>Rp 25.000</td>
                    <td><span class="status-badge status-success">Selesai</span></td>
                </tr>
                <tr>
                    <td class="tx-id">#TRX-9941</td>
                    <td>12:42</td>
                    <td>Sinta Pratama</td>
                    <td>Mie Ayam Bakso</td>
                    <td>Rp 18.000</td>
                    <td><span class="status-badge status-pending">Diproses</span></td>
                </tr>
                <tr>
                    <td class="tx-id">#TRX-9940</td>
                    <td>12:38</td>
                    <td>Roni Setiawan</td>
                    <td>Es Teh Manis x2</td>
                    <td>Rp 10.000</td>
                    <td><span class="status-badge status-danger">Dibatalkan</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
            <section class="products-section">
                <div class="section-header">
                    <h2>Daftar Produk</h2>
                    <div class="view-toggle">
                        <button class="toggle-btn active">🎛️</button>
                        <button class="toggle-btn">☰</button>
                    </div>
                </div>

                <div class="products-grid">
                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <img src="https://via.placeholder.com/300x180" alt="Nasi Campur">
                            <span class="badge badge-available">Tersedia</span>
                        </div>
                        <div class="product-info">
                            <h3>Nasi Campur Spesial</h3>
                            <p class="product-price">Rp 25.000</p>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <img src="https://via.placeholder.com/300x180" alt="Soto Ayam">
                            <span class="badge badge-available">Tersedia</span>
                        </div>
                        <div class="product-info">
                            <h3>Soto Ayam Lamongan</h3>
                            <p class="product-price">Rp 18.000</p>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <img src="https://via.placeholder.com/300x180" alt="Es Teh">
                            <span class="badge badge-empty">Habis</span>
                        </div>
                        <div class="product-info">
                            <h3>Es Teh Manis Jumbo</h3>
                            <p class="product-price">Rp 5.000</p>
                        </div>
                    </div>
                </div>

                <button class="btn-load-more">Lihat Semua Produk →</button>
            </section>
        </main>

        <!-- <aside class="sidebar-content">
            
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h3>Daftar Penjual</h3>
                    <button class="icon-btn">👤+</button>
                </div>
                <div class="seller-list">
                    <div class="seller-item">
                        <div class="seller-avatar"></div>
                        <div class="seller-detail">
                            <h4>Budi Santoso</h4>
                            <p>KEPALA SHIFT</p>
                        </div>
                        <a href="#" class="btn-link">LIHAT PERFORMA</a>
                    </div>
                    <div class="seller-item">
                        <div class="seller-avatar"></div>
                        <div class="seller-detail">
                            <h4>Siti Aminah</h4>
                            <p>ADMIN STOK</p>
                        </div>
                        <a href="#" class="btn-link">LIHAT PERFORMA</a>
                    </div>
                    <div class="seller-item">
                        <div class="seller-avatar"></div>
                        <div class="seller-detail">
                            <h4>Rizky Pratama</h4>
                            <p>KASIR UTAMA</p>
                        </div>
                        <a href="#" class="btn-link">LIHAT PERFORMA</a>
                    </div>
                </div>

                <hr class="divider">

                <div class="outlet-info">
                    <h4>INFORMASI KONTAK OUTLET</h4>
                    <div class="info-item">
                        <span class="info-icon">📞</span>
                        <div>
                            <p class="info-label">TELEPON</p>
                            <p class="info-text">+62 812-3456-7890</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">✉️</span>
                        <div>
                            <p class="info-label">EMAIL</p>
                            <p class="info-text">kantin.senayan@company.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🕒</span>
                        <div>
                            <p class="info-label">JAM OPERASIONAL</p>
                            <p class="info-text">Senin - Jumat (08:00 - 17:00)</p>
                        </div>
                    </div>
                </div> 
            </div>

            <button class="target-button">
                <h3>Target Bulanan</h3>
                <p>Klik untuk detail laporan target</p>
            </button>

            <div class="sidebar-card info-kontak-card">
                <h3>Info Kontak</h3>
                <div class="info-item">
                    <span class="info-icon">📞</span>
                    <div>
                        <p class="info-label">TELEPON</p>
                        <p class="info-text">+62 21 555 0123</p>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-icon">✉️</span>
                    <div>
                        <p class="info-label">EMAIL</p>
                        <p class="info-text">senayan@canteenflow.com</p>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-icon">🕒</span>
                    <div>
                        <p class="info-label">JAM OPERASIONAL</p>
                        <p class="info-text">07:00 - 20:00 WIB</p>
                        <p class="info-subtext">Senin - Sabtu</p>
                    </div>
                </div>
            </div>
        </aside> -->

    </div>

</body>
</html>