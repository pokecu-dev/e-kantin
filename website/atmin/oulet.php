<?php
// session_start();

require_once '../include/koneksi.php';
require_once __DIR__ . "/../include/session/adminC.php";
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_login = $_SESSION['id_user'];

// =========================================================================
// HANDLER AJAX: Jika ada request pencarian, langsung kembalikan data HTML saja
// =========================================================================
if (isset($_GET['ajax_search'])) {
    $search = $_GET['keyword'] ?? '';
    
    if (!empty($search)) {
        $query_kantin = "SELECT * FROM list_kantin WHERE NAMA_KANTIN LIKE ? ORDER BY ID DESC";
        $stmt = mysqli_prepare($conn, $query_kantin);
        $search_param = "%" . $search . "%";
        mysqli_stmt_bind_param($stmt, "s", $search_param);
    } else {
        $query_kantin = "SELECT * FROM list_kantin ORDER BY ID DESC";
        $stmt = mysqli_prepare($conn, $query_kantin);
    }

    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result_kantin = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result_kantin) > 0) {
            while ($row = mysqli_fetch_assoc($result_kantin)) {
                ?>
                <div class="child">
                    <img src="../../source/foto_kantin/<?= htmlspecialchars($row['FOTO_KANTIN'] ?? 'default.jpg') ?>"
                        alt="<?= htmlspecialchars($row['NAMA_KANTIN']) ?>">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($row['NAMA_KANTIN']) ?></h3>
                        <p class="alamat">
                            <i class="fas fa-location-dot"></i>
                            Kantin SMK Esemkita
                        </p>
                        <a href="editoutlet.php?id=<?= urlencode($row['ID']) ?>" class="edit-btn">
                            <span>Kelola Outlet</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div class='empty-state'>Kantin '" . htmlspecialchars($search) . "' tidak ditemukan.</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='empty-state'>Gagal memuat database.</div>";
    }
    exit(); // Stop eksekusi agar tidak me-render sisa HTML struktur halaman
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KantinKita - Dashboard Penjual</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; color: #1e293b; font-family: 'Poppins', sans-serif; }
        .nav-links a { text-decoration: none; color: #888; font-weight: 500; transition: 0.3s; }
        .nav-links a.active { color: #F47B20; border-bottom: 2px solid #F47B20; padding-bottom: 5px; }
        .container { width: 100%; max-width: 1400px; margin: 90px auto 40px; padding-inline: clamp(14px, 3vw, 24px); }
        .page-header { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 28px; flex-wrap: wrap; }
        .page-header h1 { font-size: clamp(24px, 3vw, 34px); font-weight: 700; }
        .header-action { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .search-box { position: relative; }
        .search-box input { width: 260px; height: 42px; border-radius: 999px; border: 1px solid #e2e8f0; padding: 0 16px 0 42px; outline: none; background: white; transition: .2s; }
        .search-box input:focus { border-color: #F47B20; }
        .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .add-btn { height: 42px; padding: 0 18px; border-radius: 999px; background: #F47B20; color: white; border: none; font-weight: 600; cursor: pointer; transition: .2s; display: flex; align-items: center; gap: 8px; }
        .add-btn:hover { background: #db6a16; }
        
        .parent { display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 115px)); gap: 24px; align-items: start; }
        .child { background: white; border-radius: 22px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.05); transition: .25s ease; display: flex; flex-direction: column; min-height: 340px; }
        .child:hover { transform: translateY(-5px); }
        .child img { width: 100%; height: 190px; object-fit: cover; }
        .card-content { padding: 18px; display: flex; flex-direction: column; flex: 1; }
        .child h3 { font-size: 18px; line-height: 1.4; margin-bottom: 10px; }
        .alamat { display: flex; align-items: flex-start; gap: 8px; color: #64748b; font-size: 13px; line-height: 1.5; margin-bottom: 20px; }
        .alamat i { color: #F47B20; margin-top: 2px; }
        .edit-btn { margin-top: auto; display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding-top: 14px; border-top: 1px solid #f1f5f9; color: #F47B20; font-size: 14px; font-weight: 600; }
        .edit-btn:hover { color: #d65f12; }
        .empty-state { grid-column: 1/-1; text-align: center; padding: 70px 20px; color: #94a3b8; }

        /* CSS MODAL POP-UP (TAMBAH OUTLET) */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999; opacity: 0; pointer-events: none; transition: all 0.3s ease; }
        .modal-overlay.show { opacity: 1; pointer-events: auto; }
        .modal-content { background: white; padding: 24px; border-radius: 24px; width: 90%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transform: scale(0.9); transition: all 0.3s ease; position: relative; }
        .modal-overlay.show .modal-content { transform: scale(1); }
        .close-modal-btn { position: absolute; top: 16px; right: 16px; background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; color: #64748b; transition: 0.2s; }
        .close-modal-btn:hover { background: #e2e8f0; color: #1e293b; }

        /* Responsive */
        @media (max-width: 768px) { .parent { grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 16px; } .child { min-height: auto; } .child img { height: 140px; } .child h3 { font-size: 15px; } .alamat { font-size: 12px; } .search-box input { width: 100%; } .header-action { width: 100%; } }
        @media (max-width: 480px) { .container { padding-inline: 12px; } .parent { grid-template-columns: repeat(2, 1fr); gap: 12px; } .child img { height: 120px; } .card-content { padding: 14px; } .child h3 { font-size: 14px; } .edit-btn { font-size: 13px; } .page-header { align-items: stretch; } .header-action { flex-direction: column; align-items: stretch; } .add-btn { justify-content: center; } }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt="Logo"></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span><span></span><span></span>
            </label>
            <ul class="nav-links">
                <li><a href="admin.php">Beranda</a></li>
                <li><a href="akun.php">Akun</a></li>
                <li><a href="menu.php">Produk</a></li>
                <li><a href="outlet.php" class="active">Kantin</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Kantin Esemkita</h1>
            <div class="header-action">
                
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="inputSearch" placeholder="Cari outlet..." oninput="liveSearchOutlet()">
                </div>

                <?php
                    $sql = "SELECT * FROM list_kantin WHERE STATUS = '1'";
                    $add = true;
                    if($conn->query($sql)->num_rows >= 10){
                        $add = false;
                    }
                    if($add):
                ?>

                        <button class="add-btn" onclick="bukaModalTambah()">
                            <i class="fas fa-plus"></i>
                            Tambah Outlet
                        </button>
                    <?php else: ?>
                        <button class="add-btn" onclick="alert('total kantin sudah 10!')">
                            <i class="fas fa-plus"></i>
                            Tambah Outlet
                        </button>
                    <?php endif; ?>
            </div>
        </div>

        <div class="parent" id="containerOutlet">
            <?php
            // Isi default saat pertama kali halaman di-load biasa tanpa filter pencarian
            $query_kantin = "SELECT * FROM list_kantin ORDER BY ID DESC";
            $stmt = mysqli_prepare($conn, $query_kantin);
            if ($stmt) {
                mysqli_stmt_execute($stmt);
                $result_kantin = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result_kantin) > 0) {
                    while ($row = mysqli_fetch_assoc($result_kantin)) {
                        ?>
                        <div class="child">
                            <img src="../../source/foto_kantin/<?= htmlspecialchars($row['FOTO_KANTIN'] ?? 'default.jpg') ?>"
                                alt="<?= htmlspecialchars($row['NAMA_KANTIN']) ?>">
                            <div class="card-content">
                                <h3><?= htmlspecialchars($row['NAMA_KANTIN']) ?></h3>
                                <p class="alamat">
                                    <i class="fas fa-location-dot"></i>
                                    Kantin SMK Esemkita
                                </p>
                                <a href="editoutlet.php?id=<?= urlencode($row['ID']) ?>" class="edit-btn">
                                    <span>Kelola Outlet</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='empty-state'>Belum ada kantin yang terdaftar.</div>";
                }
                mysqli_stmt_close($stmt);
            }
            ?>
        </div>
    </div>

    <div class="modal-overlay" id="modalTambahOutlet" onclick="tutupModalTambah(event)">
        <div class="modal-content">
            <button class="close-modal-btn" onclick="paksaTutupModal()"><i class="fas fa-times"></i></button>
            <div id="kontenIsiModal">
                <p style="text-align: center; color: #94a3b8;">Memuat form...</p>
            </div>
        </div>
    </div>

    <script>
        // =========================================================================
        // 🔍 LIVE SEARCH AJAX (Tanpa Refresh Halaman)
        // =========================================================================
function liveSearchOutlet() {
    const keyword = document.getElementById('inputSearch').value;
    const container = document.getElementById('containerOutlet');

    // UBAH dadi oulet.php (sesuai nama file aslimu)
    fetch(`oulet.php?ajax_search=1&keyword=${encodeURIComponent(keyword)}`)
        .then(response => {
            if (!response.ok) throw new Error('File tidak ditemukan (404)');
            return response.text();
        })
        .then(htmlOutput => {
            // Update isi grid tanpa reload halaman
            container.innerHTML = htmlOutput;
        })
        .catch(error => {
            console.error('Pencarian error:', error);
        });
}

        // =========================================================================
        // 📦 LOGIC MODAL FOR POP-UP
        // =========================================================================
        function bukaModalTambah() {
            const overlay = document.getElementById('modalTambahOutlet');
            const containerKonten = document.getElementById('kontenIsiModal');
            
            overlay.classList.add('show');
            
            fetch('addOutlet.php')
                .then(response => {
                    if (!response.ok) throw new Error('Gagal memuat form');
                    return response.text();
                })
                .then(html => {
                    containerKonten.innerHTML = html;
                })
                .catch(error => {
                    containerKonten.innerHTML = `<p style='color:red; text-align:center;'>${error.message}</p>`;
                });
        }

        function tutupModalTambah(e) {
            if (e.target.id === 'modalTambahOutlet') {
                paksaTutupModal();
            }
        }

        function paksaTutupModal() {
            document.getElementById('modalTambahOutlet').classList.remove('show');
        }
    </script>
</body>

</html>