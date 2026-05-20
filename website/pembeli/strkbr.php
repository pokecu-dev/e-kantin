<?php
/**
 * struckdigital.php  ─ VERSI BARU (replace file lama)
 * Membaca dari detail_transaksi, bukan dari keranjang.
 * Author: CEO Fullstack Dev
 */
session_start();
require_once '../include/koneksi.php';

if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }

$id_user = (int)$_SESSION['id_user'];
$trxId   = isset($_GET['trx']) ? (int)$_GET['trx'] : 0;

$transaction = null; $kantinName = ''; $trxIdStr = ''; $tglTrx = ''; $waktuTrx = '';
$items = []; $subtotal = 0;

if ($trxId > 0) {
    $qTrx = mysqli_query($conn, "
        SELECT t.*, k.NAMA_KANTIN FROM transaksi t
        JOIN list_kantin k ON t.id_kantin = k.ID
        WHERE t.id = $trxId AND t.id_user = $id_user LIMIT 1
    ");
    if ($transaction = mysqli_fetch_assoc($qTrx)) {
        $kantinName = $transaction['NAMA_KANTIN'];
        $tglTrx     = $transaction['tgl'];
        $waktuTrx   = $transaction['waktu'];
        $trxIdStr   = '#' . ($transaction['kode_pesanan'] ?? 'TRX-'.$trxId);

        $qItems = mysqli_query($conn, "
            SELECT nama_menu, harga, qty, subtotal
            FROM detail_transaksi WHERE id_transaksi = $trxId
        ");
        while ($row = mysqli_fetch_assoc($qItems)) {
            $row['subtotal'] = (int)$row['subtotal'];
            $subtotal += $row['subtotal'];
            $items[] = $row;
        }
    }
}
$grandTotal = $subtotal;
$f = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Struk Digital - E-Kantin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0..1&display=swap" rel="stylesheet">
<style>
  body{font-family:'Poppins',sans-serif}
  .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24}
  @media print{.no-print{display:none!important}body{background:white}}
</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col pb-20">

<!-- Top Bar -->
<header class="bg-white border-b flex justify-between items-center px-4 py-3 sticky top-0 z-50 no-print">
  <a href="pembeli.php" class="text-orange-500 hover:bg-orange-50 p-2 rounded-full">
    <span class="material-symbols-outlined">arrow_back</span>
  </a>
  <h1 class="font-bold text-lg text-orange-500">Struk Pesanan</h1>
  <button onclick="window.print()" class="text-orange-500 hover:bg-orange-50 p-2 rounded-full">
    <span class="material-symbols-outlined">print</span>
  </button>
</header>

<main class="flex-grow flex flex-col items-center py-8 px-4">
<?php if($transaction): ?>

  <!-- Sukses -->
  <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-3 no-print">
    <span class="material-symbols-outlined text-green-600 text-4xl" style="font-variation-settings:'FILL' 1">check_circle</span>
  </div>
  <h2 class="text-xl font-bold text-gray-800 mb-1 no-print">Pesanan Berhasil! 🎉</h2>
  <p class="text-sm text-gray-500 mb-6 no-print">Tunjukkan struk ini ke penjual</p>

  <!-- Kartu Struk -->
  <div class="w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden">

    <!-- Header Orange -->
    <div class="bg-orange-500 text-white px-6 py-5 text-center">
      <p class="text-xs tracking-widest uppercase opacity-80 mb-1">E-Kantin SMKN 1 Boyolangu</p>
      <h3 class="text-xl font-bold"><?= htmlspecialchars($kantinName) ?></h3>
      <div class="mt-3 flex justify-center gap-8 text-sm opacity-90">
        <div><p class="text-xs opacity-70">Kode</p><p class="font-bold"><?= htmlspecialchars($trxIdStr) ?></p></div>
        <div><p class="text-xs opacity-70">Waktu</p><p class="font-bold"><?= date('d/m/Y',strtotime($tglTrx)) ?> <?= substr($waktuTrx,0,5) ?></p></div>
      </div>
    </div>

    <!-- Status -->
    <div class="px-6 py-3 bg-orange-50 border-b border-orange-100 flex items-center gap-2">
      <span class="material-symbols-outlined text-orange-500 text-sm" style="font-variation-settings:'FILL' 1">pending</span>
      <span class="text-sm font-semibold text-orange-600">Menunggu Konfirmasi Penjual</span>
    </div>

    <!-- Items -->
    <div class="px-6 py-4">
      <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Detail Pesanan</p>
      <?php if(!empty($items)): foreach($items as $it): ?>
        <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
          <div class="flex gap-2 items-center">
            <span class="font-bold text-orange-500 text-sm"><?= (int)$it['qty'] ?>x</span>
            <span class="text-sm text-gray-700"><?= htmlspecialchars($it['nama_menu']) ?></span>
          </div>
          <span class="text-sm font-semibold"><?= $f($it['subtotal']) ?></span>
        </div>
      <?php endforeach; else: ?>
        <p class="text-sm text-gray-400 text-center py-4">Tidak ada data item.</p>
      <?php endif; ?>
    </div>

    <!-- Garis putus-putus -->
    <div class="px-6"><div class="border-t-2 border-dashed border-gray-200"></div></div>

    <!-- Total -->
    <div class="px-6 py-4">
      <div class="flex justify-between text-sm text-gray-400 mb-1"><span>Subtotal</span><span><?= $f($subtotal) ?></span></div>
      <div class="flex justify-between text-sm text-gray-400 mb-3"><span>Biaya Layanan</span><span>Rp 0</span></div>
      <div class="flex justify-between items-center bg-orange-50 rounded-xl px-4 py-3">
        <span class="font-bold text-gray-700">Total Bayar</span>
        <span class="font-extrabold text-2xl text-orange-500"><?= $f($grandTotal) ?></span>
      </div>
    </div>

    <!-- Footer -->
    <div class="px-6 pb-6 text-center text-xs text-gray-400">
      <p>Terima kasih sudah memesan! 🙏</p>
      <p>Harap tunjukkan struk kepada penjual</p>
    </div>
  </div>

  <!-- Tombol Aksi -->
  <div class="flex gap-3 mt-6 no-print w-full max-w-md">
    <a href="pembeli.php" class="flex-1 text-center py-3 rounded-2xl border-2 border-orange-500 text-orange-500 font-semibold text-sm hover:bg-orange-50 transition-colors">Kembali</a>
    <button onclick="window.print()" class="flex-1 py-3 rounded-2xl bg-orange-500 text-white font-semibold text-sm hover:bg-orange-600 transition-colors">Cetak / PDF</button>
  </div>

<?php else: ?>
  <div class="w-full max-w-md bg-white rounded-2xl shadow p-8 text-center mt-8">
    <span class="material-symbols-outlined text-red-400 text-6xl mb-4 block">error_outline</span>
    <h3 class="text-lg font-bold text-gray-700 mb-2">Transaksi Tidak Ditemukan</h3>
    <p class="text-sm text-gray-400 mb-6">Kode transaksi tidak valid atau bukan milik kamu.</p>
    <a href="pembeli.php" class="inline-block px-6 py-3 rounded-2xl bg-orange-500 text-white font-semibold hover:bg-orange-600">Ke Beranda</a>
  </div>
<?php endif; ?>
</main>

<!-- Bottom Nav -->
<nav class="fixed bottom-0 left-0 w-full flex justify-around items-center h-16 bg-white border-t no-print">
  <a href="pembeli.php" class="flex flex-col items-center text-gray-400 hover:text-orange-500 text-xs gap-1">
    <span class="material-symbols-outlined text-xl">home</span>Home
  </a>
  <a href="keranjang.php" class="flex flex-col items-center text-orange-500 text-xs gap-1">
    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">receipt_long</span>Pesanan
  </a>
  <a href="profil.php" class="flex flex-col items-center text-gray-400 hover:text-orange-500 text-xs gap-1">
    <span class="material-symbols-outlined text-xl">person</span>Profil
  </a>
</nav>
</body>
</html>