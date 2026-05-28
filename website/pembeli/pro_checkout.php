<?php

session_start();
require_once __DIR__ . '/../include/koneksi.php';

header('Content-Type: application/json');

// ── 1. Validasi session ──────────────────────────────────────
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi habis, silakan login kembali.']);
    exit();
}

// ── 2. Validasi input ────────────────────────────────────────
$id_kantin = isset($_POST['id_kantin']) ? (int)$_POST['id_kantin'] : 0;
$catatan   = isset($_POST['catatan'])   ? trim($conn->real_escape_string($_POST['catatan'])) : '';
$id_user   = (int)$_SESSION['id_user'];
$metode = isset($_POST['metode']) ? trim($conn->real_escape_string($_POST['metode'])) : '';

if ($id_kantin <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Kantin tidak valid.']);
    exit();
}

// ── 3. Ambil item keranjang user untuk kantin ini ────────────
$sql_keranjang = "
    SELECT
        k.id_keranjang,
        k.id_menu,
        k.qty,
        m.NAMA_MENU,
        m.HARGA,
        m.STOK,
        m.STATUS,
        m.ID_KANTIN
    FROM keranjang k
    JOIN tb_menu m ON k.id_menu = m.ID_MENU
    WHERE k.id_user = $id_user
      AND m.ID_KANTIN = $id_kantin
";

$result_keranjang = mysqli_query($conn, $sql_keranjang);

if (!$result_keranjang || mysqli_num_rows($result_keranjang) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong atau tidak ada item untuk kantin ini.']);
    exit();
}

$items = [];
while ($row = mysqli_fetch_assoc($result_keranjang)) {
    $items[] = $row;
}

// ── 4. Validasi stok sebelum transaksi dimulai ───────────────
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

// ── 5. Mulai Transaksi Database ──────────────────────────────
mysqli_begin_transaction($conn);

try {

    // Hitung total
    $total = 0;
    foreach ($items as $item) {
        $total += (int)$item['HARGA'] * (int)$item['qty'];
    }

    // Generate kode pesanan: ORD-YYYYMMDD-RAND4
    $kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

    // ── 5a. Insert ke tabel transaksi ────────────────────────
    $esc_catatan      = mysqli_real_escape_string($conn, $catatan);
    $esc_kode_pesanan = mysqli_real_escape_string($conn, $kode_pesanan);

    $sql_insert_trx = "
        INSERT INTO transaksi (kode_pesanan, id_kantin, id_user, tgl, waktu, total, status, catatan,metode)
        VALUES ('$esc_kode_pesanan', $id_kantin, $id_user, CURDATE(), CURTIME(), $total, 'pending', '$esc_catatan','$metode')
    ";

    if (!mysqli_query($conn, $sql_insert_trx)) {
        throw new Exception("Gagal menyimpan transaksi: " . mysqli_error($conn));
    }

    $id_transaksi = mysqli_insert_id($conn);

    // ── 5b. Insert detail_transaksi per item ─────────────────
    foreach ($items as $item) {
        $id_menu_esc  = (int)$item['id_menu'];
        $nama_esc     = mysqli_real_escape_string($conn, $item['NAMA_MENU']);
        $harga_esc    = (int)$item['HARGA'];
        $qty_esc      = (int)$item['qty'];
        $subtotal_esc = $harga_esc * $qty_esc;

        $sql_detail = "
            INSERT INTO detail_transaksi (id_transaksi, id_menu, nama_menu, harga, qty, subtotal)
            VALUES ($id_transaksi, $id_menu_esc, '$nama_esc', $harga_esc, $qty_esc, $subtotal_esc)
        ";

        if (!mysqli_query($conn, $sql_detail)) {
            throw new Exception("Gagal menyimpan detail item: " . mysqli_error($conn));
        }
    }

    // ── 5c. Kurangi stok masing-masing menu ──────────────────
    foreach ($items as $item) {
        $id_menu_esc = (int)$item['id_menu'];
        $qty_esc     = (int)$item['qty'];

        // Jika stok menjadi 0, update STATUS juga jadi 'habis'
        $sql_kurang_stok = "
            UPDATE tb_menu
            SET STOK   = STOK - $qty_esc,
                STATUS = CASE WHEN (STOK - $qty_esc) <= 0 THEN 'habis' ELSE 'tersedia' END
            WHERE ID_MENU = $id_menu_esc
        ";

        if (!mysqli_query($conn, $sql_kurang_stok)) {
            throw new Exception("Gagal update stok: " . mysqli_error($conn));
        }
    }

    // ── 5d. Hapus keranjang user untuk kantin ini ─────────────
    $sql_hapus_keranjang = "
        DELETE k FROM keranjang k
        JOIN tb_menu m ON k.id_menu = m.ID_MENU
        WHERE k.id_user = $id_user
          AND m.ID_KANTIN = $id_kantin
    ";

    if (!mysqli_query($conn, $sql_hapus_keranjang)) {
        throw new Exception("Gagal membersihkan keranjang: " . mysqli_error($conn));
    }

    // ── 6. Commit ────────────────────────────────────────────
    mysqli_commit($conn);

    if($metode === "QRIS"){
        $redirect_url = "qris.php?trx=$id_transaksi&id_kantin=$id_kantin";
    }
    else{
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
    mysqli_rollback($conn);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Checkout gagal: ' . $e->getMessage(),
    ]);
}

exit();