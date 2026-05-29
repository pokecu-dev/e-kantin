<?php

session_start();
require_once __DIR__ . '/../include/koneksi.php';

header('Content-Type: application/json');

// Validasi session 
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi habis, silakan login kembali.']);
    exit();
}

// Validasi input 
$id_user   = (int)$_SESSION['id_user'];
$id_kantin = isset($_POST['id_kantin']) ? (int)$_POST['id_kantin'] : 0;
$catatan   = isset($_POST['catatan'])   ? trim($conn->real_escape_string($_POST['catatan'])) : '';
$metode    = isset($_POST['metode'])    ? trim($conn->real_escape_string($_POST['metode'])) : '';

// Deteksi parameter beli sekarang
$id_menu_direct = isset($_POST['id_menu_direct']) ? (int)$_POST['id_menu_direct'] : 0;
$qty_direct     = isset($_POST['qty_direct'])     ? (int)$_POST['qty_direct'] : 1;

if ($id_kantin <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Kantin tidak valid.']);
    exit();
}

$items = [];

// Beli Sekarang VS Dari Keranjang 
if ($id_menu_direct > 0) {
    // beli sekarang
    $sql_direct = "SELECT ID_MENU as id_menu, NAMA_MENU, HARGA, STOK, STATUS, ID_KANTIN 
                   FROM tb_menu 
                   WHERE ID_MENU = $id_menu_direct AND ID_KANTIN = $id_kantin";
    
    $result_direct = $conn->query($sql_direct);
    
    if (!$result_direct || $result_direct->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Menu tidak ditemukan atau tidak sesuai kantin.']);
        exit();
    }
    
    $row = $result_direct->fetch_assoc();
    $row['qty'] = $qty_direct; // Pasang qty dari lemparan js
    $items[] = $row;

} else {
    // keranjang
    $sql_keranjang = "
        SELECT k.id_keranjang, k.id_menu, k.qty, m.NAMA_MENU, m.HARGA, m.STOK, m.STATUS, m.ID_KANTIN
        FROM keranjang k
        JOIN tb_menu m ON k.id_menu = m.ID_MENU
        WHERE k.id_user = $id_user AND m.ID_KANTIN = $id_kantin
    ";

    $result_keranjang = $conn->query($sql_keranjang);

    if (!$result_keranjang || $result_keranjang->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong untuk kantin ini.']);
        exit();
    }

    while ($row = $result_keranjang->fetch_assoc()) {
        $items[] = $row;
    }
}

// Validasi stok sebelum transaksi dimulai 
$stok_errors = [];
foreach ($items as $item) {
    if ($item['STATUS'] === 'habis') {
        $stok_errors[] = "Menu \"{$item['NAMA_MENU']}\" sudah habis.";
    } elseif ($item['STOK'] < $item['qty']) {
        $stok_errors[] = "Stok \"{$item['NAMA_MENU']}\" tidak cukup (sisa: {$item['STOK']}, diminta: {$item['qty']}).";
    }
}

if (!empty($stok_errors)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Beberapa item tidak bisa dipesan:',
        'errors'  => $stok_errors,
    ]);
    exit();
}

// Mulai Transaksi Database 
$conn->begin_transaction();

try {
    // Hitung total belanjaan
    $total = 0;
    foreach ($items as $item) {
        $total += (int)$item['HARGA'] * (int)$item['qty'];
    }

    // Generate kode pesanan
    $kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

    // Insert ke tabel transaksi
    $sql_insert_trx = "
        INSERT INTO transaksi (kode_pesanan, id_kantin, id_user, tgl, waktu, total, status, catatan, metode)
        VALUES ('$kode_pesanan', $id_kantin, $id_user, CURDATE(), CURTIME(), $total, 'pending', '$catatan', '$metode')
    ";

    if (!$conn->query($sql_insert_trx)) {
        throw new Exception("Gagal menyimpan transaksi.");
    }

    $id_transaksi = $conn->insert_id; // Menggunakan properti OOP $conn->insert_id

    // Insert detail_transaksi per item
    foreach ($items as $item) {
        $id_menu_esc  = (int)$item['id_menu'];
        $nama_esc     = $conn->real_escape_string($item['NAMA_MENU']);
        $harga_esc    = (int)$item['HARGA'];
        $qty_esc      = (int)$item['qty'];
        $subtotal_esc = $harga_esc * $qty_esc;

        $sql_detail = "
            INSERT INTO detail_transaksi (id_transaksi, id_menu, nama_menu, harga, qty, subtotal)
            VALUES ($id_transaksi, $id_menu_esc, '$nama_esc', $harga_esc, $qty_esc, $subtotal_esc)
        ";

        if (!$conn->query($sql_detail)) {
            throw new Exception("Gagal menyimpan detail item.");
        }
    }

    // Kurangi stok masing-masing menu
    foreach ($items as $item) {
        $id_menu_esc = (int)$item['id_menu'];
        $qty_esc     = (int)$item['qty'];

        $sql_kurang_stok = "
            UPDATE tb_menu
            SET STOK   = STOK - $qty_esc,
                STATUS = CASE WHEN (STOK - $qty_esc) <= 0 THEN 'habis' ELSE 'tersedia' END
            WHERE ID_MENU = $id_menu_esc
        ";

        if (!$conn->query($sql_kurang_stok)) {
            throw new Exception("Gagal update stok.");
        }
    }

    // Hapus keranjang HANYA JIKA belanja lewat keranjang
    if ($id_menu_direct === 0) {
        $sql_hapus_keranjang = "
            DELETE k FROM keranjang k
            JOIN tb_menu m ON k.id_menu = m.ID_MENU
            WHERE k.id_user = $id_user AND m.ID_KANTIN = $id_kantin
        ";

        if (!$conn->query($sql_hapus_keranjang)) {
            throw new Exception("Gagal membersihkan keranjang.");
        }
    }

    // Commit Transaksi 
    $conn->commit();

    if ($metode === "QRIS") {
        $redirect_url = "qris.php?trx=$id_transaksi&id_kantin=$id_kantin";
    } else {
        $redirect_url = "struckdigital.php?trx=$id_transaksi";
    }

    echo json_encode([
        'status'        => 'success',
        'message'       => 'Pesanan berhasil dibuat!',
        'id_transaksi'  => $id_transaksi,
        'kode_pesanan'  => $kode_pesanan,
        'total'         => $total,
        'redirect'      => $redirect_url,
    ]);

} catch (Exception $e) {

    $conn->rollback();
    echo json_encode([
        'status'  => 'error',
        'message' => 'Checkout gagal: ' . $e->getMessage(),
    ]);
}

exit();