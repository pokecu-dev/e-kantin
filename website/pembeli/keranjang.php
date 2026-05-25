<?php
/**
 * keranjang.php — VERSI BARU
 * Auto group by kantin pakai while loop PHP native
 * Author: CEO Fullstack Dev
 */

session_start();
require_once '../include/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = (int)$_SESSION['id_user'];

// ── Ambil SEMUA item keranjang, JOIN dengan menu & kantin ──
$query = mysqli_query($conn, "
    SELECT 
        k.id_keranjang,
        k.id_menu,
        k.qty,
        m.NAMA_MENU,
        m.HARGA,
        m.FOTO_MENU,
        m.STOK,
        m.STATUS,
        m.ID_KANTIN,
        ka.NAMA_KANTIN,
        ka.FOTO_KANTIN
    FROM keranjang k
    JOIN tb_menu m ON k.id_menu = m.ID_MENU
    JOIN list_kantin ka ON m.ID_KANTIN = ka.ID
    WHERE k.id_user = $id_user
    ORDER BY ka.ID ASC, k.id_keranjang ASC
");

// ── GROUP BY KANTIN pakai array PHP ──
$grouped = [];
while ($row = mysqli_fetch_assoc($query)) {
    $id_kantin = $row['ID_KANTIN'];
    
    // Inisialisasi jika kantin belum ada di array
    if (!isset($grouped[$id_kantin])) {
        $grouped[$id_kantin] = [
            'kantin_id'   => $id_kantin,
            'kantin_nama' => $row['NAMA_KANTIN'],
            'kantin_foto' => $row['FOTO_KANTIN'],
            'items'       => [],
            'total'       => 0,
        ];
    }
    
    $subtotal = (int)$row['HARGA'] * (int)$row['qty'];
    
    // Tambahkan item ke kantin
    $grouped[$id_kantin]['items'][] = [
        'id_keranjang' => $row['id_keranjang'],
        'id_menu'      => $row['id_menu'],
        'nama_menu'    => $row['NAMA_MENU'],
        'harga'        => (int)$row['HARGA'],
        'qty'          => (int)$row['qty'],
        'foto_menu'    => $row['FOTO_MENU'],
        'stok'         => (int)$row['STOK'],
        'status'       => $row['STATUS'],
        'subtotal'     => $subtotal,
    ];
    
    $grouped[$id_kantin]['total'] += $subtotal;
}

$total_all = array_sum(array_column($grouped, 'total'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - E-Kantin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .kr-wrap {
            max-width: 900px;
            margin: 100px auto 120px;
            padding: 0 16px;
        }

        .kr-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 0 10px;
            margin-bottom: 16px;
        }

        .kr-header .btn-back img { width: 24px; height: 24px; }

        .kr-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1A1A1A;
        }

        /* ── Card per Kantin (hasil while loop) ── */
        .kr-kantin-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        /* Header Kantin */
        .kr-kantin-head {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f5f5f5;
            margin-bottom: 14px;
        }

        .kr-kantin-head img {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            object-fit: cover;
        }

        .kr-kantin-head h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1A1A1A;
        }

        /* Item Row */
        .kr-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .kr-item:last-child { border-bottom: none; }

        .kr-item img {
            width: 70px;
            height: 70px;
            border-radius: 10px;
            object-fit: cover;
        }

        .kr-item-info { flex: 1; min-width: 0; }

        .kr-item-info .nama {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 600;
            color: #1A1A1A;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .kr-item-info .harga {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #F47B20;
        }

        .kr-item-info .stok-warn {
            margin: 4px 0 0;
            font-size: 11px;
            color: #e53935;
            font-weight: 500;
        }

        /* Qty Control */
        .kr-qty-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .kr-qty-btn {
            width: 32px;
            height: 32px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
            color: #1A1A1A;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .kr-qty-btn:hover {
            border-color: #F47B20;
            color: #F47B20;
        }

        .kr-qty-val {
            font-size: 15px;
            font-weight: 700;
            min-width: 32px;
            text-align: center;
        }

        .kr-btn-del {
            width: 32px;
            height: 32px;
            border: 1.5px solid #fee;
            border-radius: 8px;
            background: #fff;
            color: #e53935;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .kr-btn-del:hover {
            background: #fdecea;
            border-color: #f5c6c6;
        }

        /* Footer per Kantin */
        .kr-kantin-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            margin-top: 10px;
            border-top: 2px solid #f5f5f5;
        }

        .kr-kantin-total {
            font-size: 15px;
            font-weight: 700;
            color: #1A1A1A;
        }

        .kr-kantin-total span {
            color: #F47B20;
            font-size: 17px;
        }

        .kr-btn-checkout {
            padding: 12px 28px;
            border: none;
            border-radius: 12px;
            background: #F47B20;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
            text-decoration: none;
        }

        .kr-btn-checkout:hover { opacity: 0.9; }

        .kr-kantin-foot form {
            margin: 0;
            padding: 0;
        }


        /* Empty State */
        .kr-empty {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        .kr-empty .icon { font-size: 72px; margin-bottom: 12px; }

        .kr-empty h3 {
            margin: 0 0 8px;
            font-size: 18px;
            font-weight: 700;
            color: #1A1A1A;
        }

        .kr-empty p {
            margin: 0 0 24px;
            font-size: 14px;
            color: #64748b;
        }

        .kr-empty a {
            display: inline-block;
            padding: 12px 32px;
            border-radius: 12px;
            background: #F47B20;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
        }

        @media (max-width: 480px) {
            .kr-wrap { margin-top: 20px; margin-bottom: 100px; }
            .kr-item img { width: 60px; height: 60px; }
            .kr-qty-wrap { flex-direction: column; gap: 6px; }
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

<div class="kr-wrap">

    <div class="kr-header">
        <a href="pembeli.php" class="btn-back">
            <img src="../../source/icon/kembali.svg" alt="Kembali">
        </a>
        <h2>Keranjang Belanja</h2>
    </div>

    <?php if (!empty($grouped)): ?>

        <?php
        // ── WHILE LOOP: Tampilkan card per kantin ──
        foreach ($grouped as $id_kantin => $kantin):
        ?>

        <div class="kr-kantin-card">

            <!-- Header Kantin -->
            <div class="kr-kantin-head">
                <img src="../../source/foto_kantin/<?= htmlspecialchars($kantin['kantin_foto']) ?>" alt="Foto Kantin">
                <h3><?= htmlspecialchars($kantin['kantin_nama']) ?></h3>
            </div>

            <!-- Items dalam kantin ini -->
            <?php foreach ($kantin['items'] as $item): ?>

                <div class="kr-item">
                    <img src="../../source/gambar_menu/<?= htmlspecialchars($item['foto_menu']) ?>" alt="Foto Menu">

                    <div class="kr-item-info">
                        <p class="nama"><?= htmlspecialchars($item['nama_menu']) ?></p>
                        <p class="harga">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                        
                        <?php if ($item['status'] === 'habis'): ?>
                            <p class="stok-warn">⚠ Menu habis</p>
                        <?php elseif ($item['stok'] < $item['qty']): ?>
                            <p class="stok-warn">⚠ Stok kurang (sisa: <?= $item['stok'] ?>)</p>
                        <?php endif; ?>
                    </div>

                    <!-- Qty Control -->
                    <div class="kr-qty-wrap">
                        <button class="kr-qty-btn" onclick="updateQty(<?= $item['id_keranjang'] ?>, <?= $item['qty'] - 1 ?>)">−</button>
                        <span class="kr-qty-val"><?= $item['qty'] ?></span>
                        <button class="kr-qty-btn" onclick="updateQty(<?= $item['id_keranjang'] ?>, <?= $item['qty'] + 1 ?>)">+</button>
                        <button class="kr-btn-del" onclick="hapusItem(<?= $item['id_keranjang'] ?>)" title="Hapus">🗑</button>
                    </div>
                </div>

            <?php endforeach; ?>
                            
            <!-- Footer per Kantin: Total + Tombol Checkout -->
            <div class="kr-kantin-foot">
                <p class="kr-kantin-total">
                    Total: <span>Rp <?= number_format($kantin['total'], 0, ',', '.') ?></span>
                </p>
                
                <form action="checkout.php" method="GET">
                    <input type="hidden" name="id_kantin" value="<?= $row_kantin['id_kantin']; ?>">
                    <button type="submit" class="kr-btn-checkout">Checkout</button>
             </div>

        </div>

        <?php endforeach; ?>

    <?php else: ?>

        <!-- Keranjang kosong -->
        <div class="kr-empty">
            <div class="icon">🛒</div>
            <h3>Keranjang Masih Kosong</h3>
            <p>Yuk, pesan menu favoritmu sekarang!</p>
            <a href="pembeli.php">Mulai Belanja</a>
        </div>

    <?php endif; ?>

</div>

<script>
    // Update qty via AJAX
    function updateQty(idKeranjang, qtyBaru) {
        if (qtyBaru < 0) return;
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'up_keranjangDB.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            var responTeks = xhr.responseText.trim();
            if (xhr.status === 200) {
                if (responTeks !== "success") { 
                    // alert("Pesan dari sistem: " + responTeks);
                }
                location.reload();
                console.log("hai");
            } else {
                // alert('Gagal update qty');
            }
        };
        xhr.send('id_keranjang=' + idKeranjang + '&qty=' + qtyBaru);
        
    }

    // Hapus item via AJAX
    function hapusItem(idKeranjang) {
        if (!confirm('Yakin hapus item ini?')) return;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'hapus_keranjang.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert('Gagal hapus item');
            }
        };
        xhr.send('id=' + idKeranjang);
    }
</script>

</body>
</html>