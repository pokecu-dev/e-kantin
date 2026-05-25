<?php

// session_start();
require_once '../include/koneksi.php';
require_once __DIR__ . "/../include/session/pembeliC.php";

$id_user   = (int)$_SESSION['id_user'];
$id_kantin = isset($_GET['kantin']) ? (int)$_GET['kantin'] : 0;

if ($id_kantin <= 0) { header("Location: keranjang.php"); exit(); }

$q_kantin = mysqli_query($conn, "SELECT * FROM list_kantin WHERE ID = $id_kantin LIMIT 1");
$kantin   = mysqli_fetch_assoc($q_kantin);
if (!$kantin) { header("Location: keranjang.php"); exit(); }

$q_items = mysqli_query($conn, "
    SELECT k.id_keranjang, k.id_menu, k.qty,
           m.NAMA_MENU, m.HARGA, m.STOK, m.STATUS, m.FOTO_MENU
    FROM keranjang k
    JOIN tb_menu m ON k.id_menu = m.ID_MENU
    WHERE k.id_user = $id_user AND m.ID_KANTIN = $id_kantin
");

if (!$q_items || mysqli_num_rows($q_items) === 0) { header("Location: keranjang.php"); exit(); }

$items = []; $total = 0; $ada_error = false;

while ($row = mysqli_fetch_assoc($q_items)) {
    $subtotal        = (int)$row['HARGA'] * (int)$row['qty'];
    $total          += $subtotal;
    $row['subtotal'] = $subtotal;
    $row['error']    = '';

    if ($row['STATUS'] === 'habis' && $row['STOK'] <= 0) {
        $row['error'] = 'Menu ini sudah habis'; $ada_error = true;
    } elseif ($row['STOK'] < $row['qty']) {
        $row['error'] = "Stok kurang (sisa: {$row['STOK']})"; $ada_error = true;
    }
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - E-Kantin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .co-container {
            max-width: 680px;
            margin: 80px auto 120px;
            padding: 0 16px;
        }

        .co-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 0 10px;
            margin-bottom: 16px;
        }

        .co-header .btn-back img { width: 24px; height: 24px; display: block; }

        .co-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1A1A1A;
        }

        .co-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        .co-card-title {
            font-size: 11px;
            font-weight: 700;
            color: #F47B20;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 14px;
        }

        /* Kantin Info */
        .co-kantin {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .co-kantin img {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .co-kantin h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #1A1A1A;
        }

        /* Item Row */
        .co-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .co-item:last-child { border-bottom: none; }

        .co-item.error-item {
            background: #fff5f5;
            border-radius: 8px;
            padding: 12px 10px;
            margin: 4px -10px;
        }

        .co-item img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .co-item-info { flex: 1; min-width: 0; }

        .co-item-info .nama {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 3px;
            color: #1A1A1A;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .co-item-info .harga-sat {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        .co-item-error {
            display: block;
            font-size: 11px;
            color: #e53935;
            margin-top: 3px;
            font-weight: 500;
        }

        .co-item-right { text-align: right; flex-shrink: 0; }

        .co-item-right .qty-lbl {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 2px;
        }

        .co-item-right .subtotal {
            font-size: 14px;
            font-weight: 700;
            color: #F47B20;
            margin: 0;
        }

        /* Catatan */
        .co-card label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        .co-card textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: #1A1A1A;
            background: #fafafa;
            resize: vertical;
            outline: none;
            transition: border-color 0.2s;
        }

        .co-card textarea:focus {
            border-color: #F47B20;
            background: #fff;
        }

        /* Summary */
        .co-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #64748b;
            padding: 6px 0;
        }

        .co-summary-row.total {
            border-top: 2px solid #f0f0f0;
            margin-top: 8px;
            padding-top: 14px;
            font-size: 15px;
            font-weight: 700;
            color: #1A1A1A;
        }

        .co-summary-row.total span:last-child {
            color: #F47B20;
            font-size: 18px;
        }

        /* Alert */
        .co-alert {
            background: #fdecea;
            border: 1px solid #f5c6c6;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            color: #c62828;
            margin-bottom: 14px;
            display: none;
            line-height: 1.6;
        }

        .co-alert.show { display: block; }

        .co-alert ul { margin: 6px 0 0; padding-left: 18px; }

        /* Tombol */
        .co-btn {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 16px;
            background: #F47B20;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
        }

        .co-btn:hover  { opacity: 0.9; }
        .co-btn:active { transform: scale(0.98); }
        .co-btn:disabled { background: #ccc; cursor: not-allowed; opacity: 1; }

        /* Loading Overlay */
        #co-loading {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.85);
            z-index: 9999;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 14px;
        }

        #co-loading.show { display: flex; }

        .co-spinner {
            width: 46px;
            height: 46px;
            border: 4px solid #f0f0f0;
            border-top: 4px solid #F47B20;
            border-radius: 50%;
            animation: coSpin 0.75s linear infinite;
        }

        @keyframes coSpin { to { transform: rotate(360deg); } }

        #co-loading p { font-size: 14px; color: #64748b; margin: 0; }

        @media (max-width: 480px) {
            .co-container { margin-top: 20px; margin-bottom: 100px; }
            .co-item img  { width: 50px; height: 50px; }
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
                <li><a href="pembeli.php" class="active">Beranda</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

<!-- Loading -->
<div id="co-loading">
    <div class="co-spinner"></div>
    <p>Memproses pesanan kamu...</p>
</div>

<div class="co-container">

    <div class="co-header">
        <a href="keranjang.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>
        <h2>Konfirmasi Pesanan</h2>
    </div>

    <div class="co-alert" id="co-alert"></div>

    <!-- Kantin -->
    <div class="co-card">
        <p class="co-card-title">Dari Kantin</p>
        <div class="co-kantin">
            <img src="../../source/foto_kantin/<?= htmlspecialchars($kantin['FOTO_KANTIN']) ?>" alt="Foto Kantin">
            <h3><?= htmlspecialchars($kantin['NAMA_KANTIN']) ?></h3>
        </div>
    </div>

    <!-- Items -->
    <div class="co-card">
        <p class="co-card-title">Detail Pesanan</p>
        <?php foreach ($items as $item): ?>
            <div class="co-item <?= $item['error'] ? 'error-item' : '' ?>">
                <img src="../../source/gambar_menu/<?= htmlspecialchars($item['FOTO_MENU']) ?>" alt="Foto Menu">
                <div class="co-item-info">
                    <p class="nama"><?= htmlspecialchars($item['NAMA_MENU']) ?></p>
                    <p class="harga-sat">Rp <?= number_format((int)$item['HARGA'], 0, ',', '.') ?> / item</p>
                    <?php if ($item['error']): ?>
                        <span class="co-item-error">⚠ <?= htmlspecialchars($item['error']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="co-item-right">
                    <p class="qty-lbl">x<?= (int)$item['qty'] ?></p>
                    <p class="subtotal">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Catatan -->
    <div class="co-card">
        <p class="co-card-title">Catatan (Opsional)</p>
        <label for="catatan">Pesan khusus untuk penjual</label>
        <textarea id="catatan" rows="3" placeholder="Contoh: tanpa sambal, extra pedas, dll..."></textarea>
    </div>

    <!-- Ringkasan -->
    <div class="co-card">
        <p class="co-card-title">Ringkasan Pembayaran</p>
        <div class="co-summary-row">
            <span>Subtotal (<?= count($items) ?> item)</span>
            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
        </div>
        <div class="co-summary-row">
            <span>Biaya Layanan</span>
            <span>Rp 0</span>
        </div>
        <div class="co-summary-row total">
            <span>Total Pembayaran</span>
            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
        </div>
    </div>

    <button class="co-btn" id="co-btn"
        <?= $ada_error ? 'disabled' : '' ?>
        onclick="prosesCheckout()">
        <?= $ada_error ? '⚠ Ada Item Bermasalah' : 'Pesan Sekarang 🛍' ?>
    </button>

</div>

<script>
var ID_KANTIN = <?= $id_kantin ?>;

function prosesCheckout() {
    var btn     = document.getElementById('co-btn');
    var alert   = document.getElementById('co-alert');
    var loading = document.getElementById('co-loading');
    var catatan = document.getElementById('catatan').value;

    alert.classList.remove('show');
    alert.innerHTML = '';
    loading.classList.add('show');
    btn.disabled = true;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', './pro_checkout.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        loading.classList.remove('show');
        if (xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    var msg = data.message || 'Terjadi kesalahan.';
                    if (data.errors && data.errors.length > 0) {
                        msg += '<ul>';
                        for (var i = 0; i < data.errors.length; i++) {
                            msg += '<li>' + data.errors[i] + '</li>';
                        }
                        msg += '</ul>';
                    }
                    alert.innerHTML = msg;
                    alert.classList.add('show');
                    btn.disabled = false;
                }
            } catch (e) {
                alert.innerHTML = 'Respon server tidak valid. Coba lagi.';
                alert.classList.add('show');
                btn.disabled = false;
            }
        } else {
            alert.innerHTML = 'Gagal menghubungi server. Coba lagi.';
            alert.classList.add('show');
            btn.disabled = false;
        }
    };

    xhr.onerror = function () {
        loading.classList.remove('show');
        alert.innerHTML = 'Terjadi kesalahan jaringan. Coba lagi.';
        alert.classList.add('show');
        btn.disabled = false;
    };

    xhr.send('id_kantin=' + ID_KANTIN + '&catatan=' + encodeURIComponent(catatan));
}
</script>

</body>
</html>