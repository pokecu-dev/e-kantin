<?php

require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/pembeliC.php";

$id_kantin = isset($_GET['id_kantin']) ? (int)$_GET['id_kantin'] : 0;
$id_user = $_SESSION['id_users'] ?? 0;
$trx = isset($_GET['trx']) ? (int)$_GET['trx'] : 0;

$sql = "SELECT * FROM list_kantin WHERE id = $id_kantin";
$query = $conn->query($sql);
$result = $query->fetch_assoc();

$qris = $result['QRIS'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRIS Pembayaran - E-Kantin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            min-height: 100vh;
            background: var(--bg-color);
            font-family: 'Poppins', sans-serif;
        }

        .qris-page {
            min-height: 100vh;
            padding: 40px 16px 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qris-card {
            width: 100%;
            max-width: 560px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 32px;
            border: 1px solid #f0f0f0;
        }

        .qris-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .qris-title {
            margin: 0;
            font-size: clamp(1.5rem, 2vw, 2rem);
            color: var(--dark);
            font-weight: 700;
        }

        .qris-text {
            margin: 0 0 24px;
            color: #5f6c7b;
            line-height: 1.75;
            font-size: 0.98rem;
        }

        .qris-image-wrapper {
            display: flex;
            justify-content: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            padding: 24px;
            margin-bottom: 26px;
            position: relative;
        }

        .qris-image {
            width: 100%;
            max-width: 360px;
            height: auto;
            object-fit: contain;
            border-radius: 18px;
        }

        .success-overlay {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.96);
            color: #1a1a1a;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            width: 80%;
            max-width: 320px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .success-overlay h3 {
            margin: 0 0 8px 0;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .success-overlay p {
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .qris-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .qris-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
            padding: 12px 18px;
            border-radius: 14px;
            background: var(--primary);
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .qris-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .qris-btn.secondary {
            background: #f3f4f6;
            color: #1a1a1a;
            border: 1px solid #d1d5db;
        }

        .qris-btn.success-btn {
            background: #28a745 !important;
        }

        .qris-empty {
            padding: 18px 16px;
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 18px;
            color: #b91c1c;
            font-weight: 600;
            margin-bottom: 24px;
            text-align: center;
        }

        @media (max-width: 640px) {
            .qris-page {
                padding-top: 24px;
            }

            .qris-card {
                padding: 22px;
            }

            .qris-header {
                align-items: flex-start;
                margin-bottom: 16px;
            }

            .qris-actions {
                justify-content: stretch;
                row-gap: 12px;
            }

            .qris-btn {
                width: 100%;
            }

            .qris-image-wrapper {
                padding: 18px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
   
    <main class="qris-page">
        <section class="qris-card">
            <div class="qris-header">
                <h1 class="qris-title">Pembayaran QRIS</h1>
                <!-- <a href="./pembeli.php" class="qris-btn secondary">Kembali</a> -->
            </div>

            <?php if ($qris): ?>
                    <p class="qris-text" id="instruction-text">Gunakan QRIS di bawah ini untuk menyelesaikan pembayaran dengan aplikasi digital banking atau e-wallet Anda.</p>
                    
                    <div class="qris-image-wrapper">
                        <img src="/source/qris/<?= htmlspecialchars($qris, ENT_QUOTES) ?>" alt="Kode QRIS" class="qris-image" id="qris-barcode">
                    
                    <div class="success-overlay" id="success-message">
                        <h3>Pembayaran Berhasil!</h3>
                        <p>Sistem mendeteksi dana sebesar nominal transaksi telah sukses dikirim ke <b><?= htmlspecialchars($result['nama_kantin'] ?? 'Kantin', ENT_QUOTES) ?></b>.</p>
                    </div>
                </div>
                <div class="qris-actions">
                    <button type="button" class="qris-btn" id="check-pay-btn" onclick="simulasiBayar()">Konfirmasi Pembayaran</button>
                    <a href="./struckdigital.php?trx=<?= $trx ?>" class="qris-btn secondary" id="btn-struk">Lihat Struk</a>
                    <a href="./pembeli.php" class="qris-btn secondary" style="min-width: 100px;">Beranda</a>
                </div>
            <?php else: ?>
                <div class="qris-empty">Kantin ini tidak menyediakan metode QRIS.</div>
                <div class="qris-actions">
                    <a href="./pembeli.php" class="qris-btn">Kembali ke Beranda</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script>
        function simulasiBayar() {
            var btnCek = document.getElementById('check-pay-btn');
            var barcode = document.getElementById('qris-barcode');
            var alertSukses = document.getElementById('success-message');
            var teksInstruksi = document.getElementById('instruction-text');
            var btnStruk = document.getElementById('btn-struk');

            // Eksekusi langsung instan tanpa delay sedetik pun!
            if (barcode) barcode.style.opacity = "0.15";
            if (alertSukses) alertSukses.style.display = "block";
            
            if (teksInstruksi) {
                teksInstruksi.innerHTML = "✨ <b>Status Pembayaran: Berhasil (Lunas).</b> Silakan menuju kantin untuk mengambil hidangan.";
                teksInstruksi.style.color = "#155724";
            }

            if (btnCek) {
                btnCek.innerHTML = "Pembayaran Terverifikasi ✅";
                btnCek.disabled = true;
                btnCek.classList.add('success-btn');
            }
            
            if (btnStruk) {
                btnStruk.classList.remove('secondary');
                btnStruk.style.background = "var(--primary)";
                btnStruk.style.color = "#ffffff";
            }
        }
    </script>
    
</body>
</html>
