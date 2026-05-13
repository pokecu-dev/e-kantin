<?php
session_start();
require_once '../include/koneksi.php';

$trxId = isset($_GET['trx']) ? (int)$_GET['trx'] : 0;

$transaction = null;
$kantinName = '';
$trxIdStr = '';
$tglTrx = '';
$waktuTrx = '';

$items = [];
$subtotal = 0;

if ($trxId > 0) {
    $qTrx = mysqli_query($conn, "
        SELECT t.*, k.NAMA_KANTIN
        FROM transaksi t
        JOIN list_kantin k ON t.id_kantin = k.ID
        WHERE t.id = $trxId
        LIMIT 1
    ");
    if ($transaction = mysqli_fetch_assoc($qTrx)) {
        $kantinName = $transaction['NAMA_KANTIN'];
        $tglTrx = $transaction['tgl'];
        $waktuTrx = $transaction['waktu'];
        $trxIdStr = '#TRX-' . $trxId;
    }

    // ambil item dari keranjang user (karena belum ada tabel detail transaksi pada init.sql)
    // catatan: ini sesuai instruksi “sesuaikan struk dengan blanjaan” walau penyimpanan item belum dipindah dari keranjang.
    $id_user = isset($transaction['id_user']) ? (int)$transaction['id_user'] : (int)($_SESSION['id_user'] ?? 0);

    $qItems = mysqli_query($conn, "
        SELECT m.NAMA_MENU, m.HARGA, m.FOTO_MENU, k.qty
        FROM keranjang k
        JOIN tb_menu m ON k.id_menu = m.id_menu
        WHERE k.id_user = $id_user
    ");

    while ($row = mysqli_fetch_assoc($qItems)) {
        $row['HARGA'] = (int)$row['HARGA'];
        $row['qty'] = (int)$row['qty'];
        $row['line_total'] = $row['HARGA'] * $row['qty'];
        $subtotal += $row['line_total'];
        $items[] = $row;
    }
}

$tax = (int)round($subtotal * 0.10);
$grandTotal = $subtotal + $tax;

$defFormat = function($n) {
    return 'Rp ' . number_format((int)$n, 0, ',', '.');
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Canteen Receipt - Kantin Sejahtera</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "secondary-fixed": "#e5e2e3",
                    "on-primary-container": "#623200",
                    "on-error-container": "#93000a",
                    "error": "#ba1a1a",
                    "error-container": "#ffdad6",
                    "surface": "#f8f9fa",
                    "on-secondary-container": "#636263",
                    "on-error-container": "#93000a",
                    "on-error": "#ffffff",
                    "on-background": "#191c1d",
                    "outline": "#897362",
                    "surface-container": "#edeeef",
                    "tertiary-fixed-dim": "#c0c7d6",
                    "on-tertiary-fixed-variant": "#404754",
                    "secondary": "#5f5e5f",
                    "inverse-surface": "#2e3132",
                    "on-surface-variant": "#564334",
                    "primary": "#904d00",
                    "on-secondary": "#ffffff",
                    "on-primary-fixed": "#2f1500",
                    "on-tertiary": "#ffffff",
                    "on-surface": "#191c1d",
                    "surface-container-low": "#f3f4f5",
                    "primary-fixed-dim": "#ffb77d",
                    "outline-variant": "#ddc1ae",
                    "surface-container-lowest": "#ffffff",
                    "tertiary-container": "#a3aab9",
                    "surface-variant": "#e1e3e4",
                    "surface-bright": "#f8f9fa",
                    "tertiary": "#585f6c",
                    "on-tertiary-fixed": "#151c27",
                    "inverse-on-surface": "#f0f1f2",
                    "tertiary-fixed": "#dce2f3",
                    "primary-container": "#ff8c00",
                    "inverse-primary": "#ffb77d",
                    "on-primary-fixed-variant": "#6e3900",
                    "secondary-fixed-dim": "#c8c6c7",
                    "on-secondary-fixed": "#1b1b1c",
                    "on-secondary-fixed-variant": "#474647",
                    "background": "#f8f9fa"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "stack-sm": "0.5rem",
                    "stack-md": "1.5rem",
                    "container-padding": "2rem",
                    "stack-lg": "2.5rem",
                    "item-row-padding": "1rem 0"
            },
            "fontFamily": {
                    "price-total": ["Hanken Grotesk"],
                    "metadata": ["Inter"],
                    "receipt-header": ["Hanken Grotesk"],
                    "thank-you": ["Hanken Grotesk"],
                    "item-detail": ["Inter"],
                    "item-name": ["Inter"]
            },
            "fontSize": {
                    "price-total": ["36px", {"lineHeight": "44px", "letterSpacing": "-0.03em", "fontWeight": "700"}],
                    "metadata": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500"}],
                    "receipt-header": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "thank-you": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "item-detail": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "item-name": ["16px", {"lineHeight": "24px", "fontWeight": "600"}]
            }
          }
        }
      }
    </script>
<style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .receipt-shadow { box-shadow: 0px 4px 20px rgba(0,0,0,0.05); }
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .receipt-container { box-shadow: none; border: 1px solid #E5E7EB; }
        }
</style>
</head>
<body class="bg-background text-on-surface min-h-screen flex flex-col font-metadata">
<header class="bg-surface dark:bg-surface-dim flex justify-between items-center w-full px-container-padding py-stack-sm max-w-[480px] mx-auto sticky top-0 z-50">
<button class="text-primary dark:text-primary-fixed-dim hover:bg-surface-container dark:hover:bg-surface-container-high transition-colors p-2 rounded-full Active: scale-95 transition-transform duration-150">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</button>
<h1 class="font-receipt-header text-receipt-header font-bold text-primary dark:text-primary-fixed-dim">CanteenReceipt</h1>
<div class="flex gap-2">
<button class="text-primary dark:text-primary-fixed-dim hover:bg-surface-container dark:hover:bg-surface-container-high transition-colors p-2 rounded-full">
<span class="material-symbols-outlined" data-icon="download">download</span>
</button>
<button class="text-primary dark:text-primary-fixed-dim hover:bg-surface-container dark:hover:bg-surface-container-high transition-colors p-2 rounded-full">
<span class="material-symbols-outlined" data-icon="mail">mail</span>
</button>
<button class="text-primary dark:text-primary-fixed-dim hover:bg-surface-container dark:hover:bg-surface-container-high transition-colors p-2 rounded-full">
<span class="material-symbols-outlined" data-icon="share">share</span>
</button>
</div>
</header>

<main class="flex-grow flex flex-col items-center py-stack-lg px-4 overflow-y-auto pb-24">
<div class="w-full max-w-[480px] bg-white receipt-shadow rounded-xl border-2 border-[#F3F4F6] overflow-hidden flex flex-col">

<section class="flex flex-col items-center pt-stack-lg pb-stack-md px-container-padding text-center">
<div class="w-16 h-16 bg-primary-container rounded-full flex items-center justify-center mb-4 text-on-primary-container shadow-sm">
<span class="material-symbols-outlined text-4xl" data-icon="restaurant" style="font-variation-settings: 'FILL' 1;">restaurant</span>
</div>
<h2 class="font-receipt-header text-receipt-header text-primary mb-1"><?= htmlspecialchars($kantinName ?: 'Kantin Sejahtera') ?></h2>
<p class="font-metadata text-metadata text-on-surface-variant uppercase tracking-widest">Transaction Successful</p>
<div class="mt-stack-md w-full flex justify-between items-end border-b border-outline-variant pb-stack-sm">
<div class="text-left">
<p class="font-metadata text-metadata text-secondary">ID: <?= htmlspecialchars($trxIdStr ?: '#TRX-') ?></p>
<p class="font-metadata text-metadata text-secondary"><?= htmlspecialchars($tglTrx ?: date('d M Y')) ?> • <?= htmlspecialchars(substr($waktuTrx ?: date('H:i'),0,5)) ?> </p>
</div>
</div>
</section>

<section class="px-container-padding flex flex-col gap-0">
<?php if (!empty($items)): ?>
    <?php foreach ($items as $it): ?>
        <div class="flex justify-between items-start py-4 border-b border-[#F3F4F6]">
            <div class="flex gap-3">
                <span class="font-item-name text-item-name text-primary"><?= (int)$it['qty'] ?>x</span>
                <div class="flex flex-col">
                    <span class="font-item-name text-item-name text-on-surface"><?= htmlspecialchars($it['NAMA_MENU']) ?></span>
                    <span class="font-item-detail text-item-detail text-secondary">&nbsp;</span>
                </div>
            </div>
            <span class="font-item-name text-item-name text-on-surface"><?= $defFormat($it['line_total']) ?></span>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="flex justify-between items-start py-4 border-b border-[#F3F4F6]">
        <div class="flex gap-3">
            <span class="font-item-name text-item-name text-primary">0x</span>
            <div class="flex flex-col">
                <span class="font-item-name text-item-name text-on-surface">Tidak ada item</span>
                <span class="font-item-detail text-item-detail text-secondary">Silakan checkout dari keranjang.</span>
            </div>
        </div>
        <span class="font-item-name text-item-name text-on-surface"><?= $defFormat(0) ?></span>
    </div>
<?php endif; ?>
</section>

<section class="mt-stack-sm mb-stack-md">
<div class="px-container-padding py-stack-sm flex flex-col gap-2">
<div class="flex justify-between items-center text-on-surface-variant">
<span class="font-metadata text-metadata">Subtotal</span>
<span class="font-metadata text-metadata"><?= $defFormat($subtotal) ?></span>
</div>

</div>
<div class="mx-4 bg-[#FFF7ED] rounded-lg px-container-padding py-stack-md flex justify-between items-center">
<span class="font-thank-you text-thank-you text-primary">Total</span>
<span class="font-price-total text-price-total text-primary"><?= $defFormat($grandTotal) ?></span>
</div>
</section>

<section class="bg-surface-container-low pt-stack-md pb-stack-lg px-container-padding flex flex-col items-center text-center">
  <div class="flex flex-col items-center mb-stack-md">
  
  <div class="mt-stack-lg flex gap-4 no-print">
    <button class="px-6 py-2 border border-outline-variant rounded-full font-metadata text-metadata text-on-surface hover:bg-surface-container transition-colors" onclick="window.print()">
      PDF Receipt
    </button>
    <button class="px-6 py-2 border border-outline-variant rounded-full font-metadata text-metadata text-on-surface hover:bg-surface-container transition-colors">
      Help Center
    </button>
  </div>
</section>

</div>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 bg-surface-container-lowest dark:bg-inverse-surface border-t border-outline-variant dark:border-outline shadow-sm md:hidden">
<a class="flex flex-col items-center justify-center text-on-secondary-container dark:text-on-secondary-fixed-variant hover:text-primary dark:hover:text-primary-fixed Active: opacity-80 transition-opacity" href="#">
<span class="material-symbols-outlined" data-icon="home">home</span>
<span class="font-metadata text-metadata">Home</span>
</a>
<a class="flex flex-col items-center justify-center text-primary dark:text-primary-fixed-dim font-bold hover:text-primary dark:hover:text-primary-fixed Active: opacity-80 transition-opacity" href="#">
<span class="material-symbols-outlined" data-icon="receipt_long" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
<span class="font-metadata text-metadata">History</span>
</a>
<a class="flex flex-col items-center justify-center text-on-secondary-container dark:text-on-secondary-fixed-variant hover:text-primary dark:hover:text-primary-fixed Active: opacity-80 transition-opacity" href="#">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="font-metadata text-metadata">Profile</span>
</a>
</nav>
</body>
</html>

