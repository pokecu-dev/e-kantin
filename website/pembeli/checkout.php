<?php
// session_start();
require_once __DIR__ . '/../include/koneksi.php'; 
require_once __DIR__ . "/../include/session/pembeliC.php";

$id_user   = (int)$_SESSION['id_user'];
$id_kantin = isset($_GET['id_kantin']) ? (int)$_GET['id_kantin'] : 0;

// Beli Sekarang
$id_menu_direct = isset($_GET['id_menu']) ? (int)$_GET['id_menu'] : 0;
$qty_direct = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;

// if ($id_kantin <= 0) { header("Location: keranjang.php"); exit(); }
if($id_kantin <= 0) {
    echo "<script>window.location.href = 'keranjang.php';</script>";
    exit(); 
}

$q_kantin = $conn->query("SELECT * FROM list_kantin WHERE ID = $id_kantin LIMIT 1");
$kantin   = $q_kantin->fetch_assoc(); 

// if (!$kantin) { header("Location: keranjang.php"); exit(); }
if(!$kantin) {
    echo "<script>window.location.href = 'keranjang.php';</script>";
    exit();
}
$items = []; $total = 0; $ada_error = false;

if ($id_menu_direct > 0) {
    // Beli Sekarang
    $q_items = $conn->query("
        SELECT 0 as id_keranjang, ID_MENU as id_menu, $qty_direct as qty,
               NAMA_MENU, HARGA, STOK, STATUS, FOTO_MENU
        FROM tb_menu 
        WHERE ID_MENU = $id_menu_direct AND ID_KANTIN = $id_kantin
    ");
} else {
    //  Keranjang
    $q_items = $conn->query("
        SELECT k.id_keranjang, k.id_menu, k.qty,
               m.NAMA_MENU, m.HARGA, m.STOK, m.STATUS, m.FOTO_MENU
        FROM keranjang k
        JOIN tb_menu m ON k.id_menu = m.ID_MENU
        WHERE k.id_user = $id_user AND m.ID_KANTIN = $id_kantin
    ");
}

// if (!$q_items || $q_items->num_rows === 0) { header("Location: keranjang.php"); exit(); }
if (!$q_items || $q_items->num_rows === 0) {
    echo "<script>window.location.href = 'keranjang.php';</script>";
    exit();
}


while ($row = $q_items->fetch_assoc()) {
    $subtotal        = (int)$row['HARGA'] * (int)$row['qty'];
    $total          += $subtotal;
    $row['subtotal'] = $subtotal;
    $row['error']    = '';

    if ($row['STATUS'] === 'habis' || (int)$row['STOK'] <= 0) {
        $row['error'] = 'Menu ini sudah habis'; $ada_error = true;
    } elseif ((int)$row['STOK'] < (int)$row['qty']) {
        $row['error'] = "Stok kurang (sisa: {$row['STOK']})"; $ada_error = true;
    }
    $items[] = $row;
}

if ($ada_error && $id_menu_direct > 0) {
    echo "<script>
            alert('Waduh! Menu ini baru saja habis diborong. Silakan pilih menu lezat lainnya!');
            window.location.href = 'pembeli.php';
          </script>";
    exit();
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
            margin: 20px auto 120px;
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

    <!-- metode -->

    <div class="co-card">
        <p class="co-card-title">Metode Pembayaran</p>
        <select name="metode" id="metode">
            <option value="CASH">cash</option>
            <?php if($kantin['QRIS']): ?>
                <option value="QRIS">qris</option>
            
            <?php else: ?>
                <option value="QRIS" disabled>qris:tidak tersedia</option>
            
            <?php endif; ?>
        </select>

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
    var metode = document.getElementById('metode').value;
    var ID_MENU_DIRECT = <?= $id_menu_direct ?>;
    var QTY_DIRECT = <?= $qty_direct ?>;

    console.log(metode);

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

    xhr.send('id_kantin=' + ID_KANTIN + 
         '&id_menu_direct=' + ID_MENU_DIRECT + 
         '&qty_direct=' + QTY_DIRECT + 
         '&catatan=' + encodeURIComponent(catatan) + 
         '&metode=' + encodeURIComponent(metode));}

</script>

</body>
</html>