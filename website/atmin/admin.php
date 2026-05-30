<?php

require_once __DIR__ . "/../include/koneksi.php";

$totalProduk = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM tb_menu
");
$dataProduk = mysqli_fetch_assoc($totalProduk);

$totalUser = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM users
");
$dataUser = mysqli_fetch_assoc($totalUser);

$totalOutlet = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM list_kantin
");
$dataOutlet = mysqli_fetch_assoc($totalOutlet);

$produkHabis = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM tb_menu
    WHERE STOK = 0
");
$dataHabis = mysqli_fetch_assoc($produkHabis);

// PERBAIKAN QUERY: Menggunakan GROUP BY sebagai ganti DISTINCT agar relasi LEFT JOIN tidak merusak baris transaksi terbaru
$transaksi = mysqli_query($conn, "
    SELECT 
        t.ID_TRANSAKSI,
        t.TOTAL,
        t.STATUS,
        MAX(k.NAMA_KANTIN) as NAMA_KANTIN
    FROM transaksi t
    LEFT JOIN detail_transaksi dt ON t.ID_TRANSAKSI = dt.ID_TRANSAKSI
    LEFT JOIN tb_menu m ON dt.ID_MENU = m.ID_MENU
    LEFT JOIN list_kantin k ON m.ID_KANTIN = k.ID
    GROUP BY t.ID_TRANSAKSI
    ORDER BY t.ID_TRANSAKSI DESC
    LIMIT 5
");

$terlaris = mysqli_query($conn, "
    SELECT 
        m.NAMA_MENU,
        m.RATING,
        m.FOTO_MENU,
        k.NAMA_KANTIN
    FROM tb_menu m
    LEFT JOIN list_kantin k ON m.ID_KANTIN = k.ID
    WHERE m.RATING IS NOT NULL
    ORDER BY m.RATING DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-y: scroll;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gray);
            color: var(--text-dark);
            line-height: 1.5;
            margin: 0;
            padding: 0;
            padding-right: 0px !important;
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

        .container {
            width: 100%;
            max-width: 1400px;
            margin: auto;
            padding: 24px;
            margin-top: 70px;
        }

        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            color: #64748b;
        }

        .stats-scroll {
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .stats-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .stats-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            width: 100%;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .prod-orange .icon-box {
            background: #fff7ed;
            color: #ff7e14;
        }

        .user-blue .icon-box {
            background: #eff6ff;
            color: #3b82f6;
        }

        .shop-green .icon-box {
            background: #fff1f2;
            color: #f43f5e;
        }

        .empty-red .icon-box {
            background: #f0fdf4;
            color: #10b981;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-content span {
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-content h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.1;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.8fr 1.2fr;
            gap: 24px;
            align-items: start;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }

        .table {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .table-row {
            display: grid;
            grid-template-columns: 0.6fr 1.2fr 1.2fr 1fr;
            padding: 14px 16px;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #334155;
            transition: background-color 0.2s ease;
        }

        .table-row:not(.table-header):hover {
            background-color: #f8fafc;
        }

        .table-header {
            background-color: #f8fafc;
            border-radius: 10px;
            font-weight: 700;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 16px;
        }

        .table-row div:first-child {
            font-weight: 600;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #d97706;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #059669;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .rating-container {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .rating-container h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }

        .rating-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .rating-item {
            background: #fff7ed;
            border-left: 5px solid #ff7e14;
            padding: 14px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 16px;
            font-family: 'Poppins', sans-serif;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .rating-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.08);
        }

        .rating-img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .rating-img-fallback {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
        }

        .rating-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .rating-info .menu-title {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
        }

        .rating-info .kantin-name {
            font-size: 12px;
            color: #ea580c;
            font-weight: 500;
        }

        .rating-score-box {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #F47B20;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .rating-score-box i {
            color: #face15;
        }

        @media (min-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 24px;
            }

            .stat-card {
                padding: 24px;
                gap: 18px;
            }

            .icon-box {
                width: 58px;
                height: 58px;
                font-size: 22px;
            }

            .stat-content h2 {
                font-size: 32px;
            }
        }

        @media (max-width: 900px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .icon-box {
                display: none !important;
            }
        }

        @media (max-width: 576px) {
            .table-row {
                grid-template-columns: 0.5fr 1.1fr 1.2fr 1.2fr;
                padding: 12px 6px;
                font-size: 12px;
            }

            .status-badge {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <ul class="nav-links">
                <li><a href="admin.php" class="active">Beranda</a></li>
                <li><a href="akun.php">Akun</a></li>
                <li><a href="menu.php">Produk</a></li>
                <li><a href="oulet.php">Kantin</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
            <p>Pantau semua aktivitas kantin secara real-time.</p>
        </div>

        <div class="stats-scroll">
            <div class="stats-grid">
                <div class="stat-card prod-orange">
                    <div class="icon-box">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-content">
                        <span>Total Produk</span>
                        <h2><?= $dataProduk['total'] ?? 0 ?></h2>
                    </div>
                </div>

                <div class="stat-card user-blue">
                    <div class="icon-box">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <span>Total User</span>
                        <h2><?= $dataUser['total'] ?? 0 ?></h2>
                    </div>
                </div>

                <div class="stat-card shop-green">
                    <div class="icon-box">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="stat-content">
                        <span>Total Kantin</span>
                        <h2><?= $dataOutlet['total'] ?? 0 ?></h2>
                    </div>
                </div>

                <div class="stat-card empty-red">
                    <div class="icon-box">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <span>Produk Habis</span>
                        <h2><?= $dataHabis['total'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <h3>Transaksi Terbaru</h3>
                <div class="table">
                    <div class="table-row table-header">
                        <div>ID</div>
                        <div>Kantin</div>
                        <div>Total Bayar</div>
                        <div>Status</div>
                    </div>

                    <?php if (mysqli_num_rows($transaksi) > 0): ?>
                        <?php while ($trx = mysqli_fetch_assoc($transaksi)): ?>
                            <?php
                            $statusClass = '';
                            $status_check = strtolower($trx['STATUS']);

                            if ($status_check == 'pending') {
                                $statusClass = 'badge-warning';
                            } elseif ($status_check == 'success' || $status_check == 'selesai') {
                                $statusClass = 'badge-success';
                            } else {
                                $statusClass = 'badge-danger';
                            }
                            ?>
                            <div class="table-row">
                                <div style="color: #ff7e14;">#<?= htmlspecialchars($trx['ID_TRANSAKSI']) ?></div>
                                <div style="font-weight: 500; color: #64748b;">
                                    <?= htmlspecialchars($trx['NAMA_KANTIN'] ?? 'KantinKita') ?>
                                </div>
                                <div style="font-weight: 600; color: #1e293b;">
                                    Rp <?= number_format($trx['TOTAL'], 0, ',', '.') ?>
                                </div>
                                <div>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <span style="font-size: 8px; margin-right: 4px;">●</span>
                                        <?= htmlspecialchars($trx['STATUS']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="padding: 20px; text-align: center; color: #64748b;">Belum ada transaksi.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rating-container">
                <h3>Produk Rating Tertinggi</h3>
                <div class="rating-list">
                    <?php if (mysqli_num_rows($terlaris) > 0): ?>
                        <?php while ($top = mysqli_fetch_assoc($terlaris)): ?>
                            <div class="rating-item">
                                <?php if (!empty($top['FOTO_MENU'])): ?>
                                    <img src="../../source/gambar_menu/<?= htmlspecialchars($top['FOTO_MENU']) ?>" alt="<?= htmlspecialchars($top['NAMA_MENU']) ?>" class="rating-img">
                                <?php else: ?>
                                    <div class="rating-img-fallback">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="rating-info">
                                    <span class="menu-title"><?= htmlspecialchars($top['NAMA_MENU']) ?></span>
                                    <span class="kantin-name">🏪 <?= htmlspecialchars($top['NAMA_KANTIN'] ?? 'KantinKita') ?></span>
                                </div>

                                <div class="rating-score-box">
                                    <i class="fas fa-star"></i>
                                    <span><?= number_format($top['RATING'] ?? 0, 1) ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="padding: 20px; text-align: center; color: #64748b;">Belum ada rating produk.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div> 
    </div> </body>
</html>