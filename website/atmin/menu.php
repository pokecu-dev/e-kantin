<?php
require_once __DIR__ . "/../include/koneksi.php";

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'soft_delete') {
    header('Content-Type: application/json');
    
    $id_menu = intval($_POST['id_menu']);
    
    $query_delete = mysqli_query($conn, "UPDATE tb_menu SET STATUS = 'nonaktif' WHERE ID_MENU = $id_menu");
    
    if ($query_delete) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}


$sql_total = "SELECT COUNT(*) AS total FROM tb_menu WHERE STATUS != 'nonaktif'";
$total_produk = $conn->query($sql_total)->fetch_assoc()['total'] ?? 0;

$sql_low = "SELECT COUNT(*) AS low_stock FROM tb_menu WHERE STOK <= 5 AND STATUS != 'nonaktif'";
$low_stock = $conn->query($sql_low)->fetch_assoc()['low_stock'] ?? 0;

$sql_ready = "SELECT COUNT(*) AS ready FROM tb_menu WHERE STATUS = 'tersedia'";
$ready = $conn->query($sql_ready)->fetch_assoc()['ready'] ?? 0;

$sql_rating = "SELECT AVG(RATING) AS avg_rating FROM tb_menu WHERE STATUS != 'nonaktif'";
$avg_rating = $conn->query($sql_rating)->fetch_assoc()['avg_rating'] ?? 0;


$search = $_GET['query'] ?? '';

if ($search !== '') {
    $keyword = "%$search%";

    $sql = "SELECT * FROM tb_menu
            WHERE (NAMA_MENU LIKE ?
            OR KATEGORI LIKE ?
            OR CAST(ID_MENU AS CHAR) LIKE ?
            OR CAST(ID_KANTIN AS CHAR) LIKE ?)
            AND STATUS != 'nonaktif'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $keyword, $keyword, $keyword, $keyword);
    $stmt->execute();
    $query = $stmt->get_result();
} else {
    $query = $conn->query("SELECT * FROM tb_menu WHERE STATUS != 'nonaktif' ORDER BY ID_MENU DESC");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #f36f20;
            --bg-gray: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --white: #ffffff;

            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.05);
            --radius: 18px;

            --col-product: 2fr;
            --col-category: 1fr;
            --col-price: 1fr;
            --col-stock: 1fr;
            --col-status: .7fr;
            --col-action: .5fr;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gray);
            color: var(--text-dark);
            line-height: 1.5;
        }

        /* =========================
        NAVBAR
        ========================= */
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

        /* =========================
        CONTAINER
        ========================= */
        .container {
            width: 100%;
            max-width: 1400px;
            margin-inline: auto;
            padding: 24px;
            margin-top: 100px;
        }

        /* =========================
        HEADER
        ========================= */
        .header {
            margin-bottom: 30px;
        }

        .header-title {
            width: 100%;
        }

        .header-title h1 {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            margin-bottom: 10px;
        }

        .header-title p {
            color: var(--text-muted);
            margin-top: 10px;
        }

        /* =========================
        SEARCH
        ========================= */
        .input-group {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            background: #fff7ed;
            padding: 18px;
            border-radius: var(--radius);
            border: 1px solid #fed7aa;
        }

        .input-group input {
            flex: 1 1 300px;
            min-width: 0;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            font-size: 14px;
            outline: none;
        }

        .input-group input:focus {
            border-color: var(--primary-orange);
        }

        .btn-orange {
            border: none;
            background: var(--primary-orange);
            color: white;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        .btn-orange:hover {
            opacity: .9;
        }

        /* =========================
        STATS
        ========================= */
        .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: var(--radius);
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--shadow-soft);
    border: 1px solid var(--border-color);
}

.icon-box {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.stat-info span {
    font-size: 12px;
    font-weight: 600;
}

.stat-info h2 {
    font-size: 26px;
}

.card-total {
    background: #fff;
    border-color: #ffedd5 !important;
}

.card-total .icon-box {
    background: #ff7e14 !important;
    color: white !important;
}

.card-total .stat-info span { 
    color: #c2410c !important; 
}

.card-total .stat-info h2 { 
    color: #7c2d12 !important; 
}

.card-low {
    background: #fff;
    border-color: #fef08a !important;
}

.card-low .icon-box {
    background: #eab308 !important;
    color: white !important;
}

.card-low .stat-info span { 
    color: #a16207 !important; 
}

.card-low .stat-info h2 { 
    color: #713f12 !important; 
}

.card-habis {
    background: #fff !important;
    border-color: #ffe4e6 !important;
}

.card-habis .icon-box {
    background: #f43f5e !important;
    color: white !important;
}

.card-habis .stat-info span { 
    color: #be123c !important; 
}

.card-habis .stat-info h2 { 
    color: #881337 !important; 
}
        /* =========================
        DATA CARD & TOOLBAR
        ========================= */
        .data-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .toolbar {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .search-input {
            flex: 1 1 280px;
            min-width: 0;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: #f8fafc;
        }

        .toolbar>div {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-page {
            border: 1px solid var(--border-color);
            background: white;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: .2s;
        }

        .btn-page:hover {
            background: #f8fafc;
        }

        /* =========================
        TABLE GRID
        ========================= */
        .grid-wrapper {
            background-color: var(--white);
            width: 100%;
            border-bottom: 1px solid var(--border-color);
            overflow-x: auto;
        }

        .grid-header,
        .grid-row {
            min-width: 900px;
            display: grid;
            grid-template-columns: var(--col-product) var(--col-category) var(--col-price) var(--col-stock) var(--col-status) var(--col-action);
            gap: 16px;
            align-items: center;
            padding: 18px 20px;
        }

        .grid-header {
            background: #fafafa;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
        }

        .grid-row {
            border-bottom: 1px solid var(--border-color);
            transition: .2s;
        }

        .grid-row:hover {
            background: #f8fafc;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .product-info strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .img-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* =========================
        BADGES
        ========================= */
        .badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-gray {
            background: #e2e8f0;
            color: #475569;
        }

        /* =========================
        ACTIONS
        ========================= */
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            font-size: 18px;
            cursor: pointer;
        }

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
    padding: 20px;
    box-sizing: border-box;
}

.modal-overlay.show {
    opacity: 1;
    pointer-events: auto;
}

.modal-content {
    background: var(--white);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    border-radius: var(--radius);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    transform: scale(0.9);
    transition: all 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-header {
    padding: 20px 24px;
    background: #fafafa;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modal-header h2 {
    font-size: 18px;
    color: var(--text-dark);
    font-weight: 600;
}

.close-btn {
    font-size: 28px;
    font-weight: bold;
    color: var(--text-muted);
    cursor: pointer;
    transition: color 0.2s;
}

.close-btn:hover {
    color: #ef4444;
}

.modal-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.modal-body .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.modal-body label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
}

.modal-body input,
.modal-body select {
    width: 100%;
    height: 44px;
    padding: 0 14px;
    font-size: 14px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    outline: none;
    box-sizing: border-box;
    background: var(--white);
}

.modal-body input:focus,
.modal-body select:focus {
    border-color: var(--primary-orange);
    box-shadow: 0 0 0 3px rgba(243, 111, 32, 0.15);
}

.input-counter-group {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
}

.input-counter-group button {
    height: 44px;
    padding: 0 16px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.input-counter-group button:hover {
    background: #e2e8f0;
    color: #1e293b;
    border-color: #cbd5e1;
}

.input-counter-group input {
    text-align: center;
    font-weight: 600;
    background: #f8fafc;
}

.btn-submit-modal {
    background: var(--primary-orange);
    color: var(--white);
    border: none;
    height: 46px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.2s;
    width: 100%;
}

.btn-submit-modal:hover {
    background: #e05d1a;
}

.custom-file-upload {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    height: 44px;
    background-color: #fff7ed;
    border: 2px dashed #fed7aa;
    color: var(--primary-orange);
    border-radius: 10px;
    padding: 0 14px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    box-sizing: border-box;
    transition: all 0.2s ease;
}

.custom-file-upload:hover {
    background-color: #ffedd5;
    border-color: var(--primary-orange);
}

#file-chosen {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 90%;
}
        /* =========================
        MOBILE RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar>div {
                width: 100%;
            }

            .btn-page {
                flex: 1;
            }

            .input-group {
                padding: 14px;
            }

            .stat-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"><img src="../../source/icon/logo1.svg" alt=""></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <ul class="nav-links">
                <li><a href="admin.php">Beranda</a></li>
                <li><a href="akun.php">Akun</a></li>
                <li><a href="menu.php" class="active">Produk</a></li>
                <li><a href="oulet.php">Kantin</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">

        <div class="header">
            <div class="header-title">
                <h1>Kelola Produk</h1>
                <form action="" method="GET" class="input-group">
                    <input type="text" name="query" placeholder="Cari menu..." value="<?= htmlspecialchars($search ?? '') ?>">
                    <button type="submit" class="btn-orange">Cari</button>
                </form>
              
            </div>
        </div>

        <?php
        $total_produk = $conn->query("SELECT COUNT(*) AS total FROM tb_menu")->fetch_assoc()['total'] ?? 0;
        $stok_rendah = $conn->query("SELECT COUNT(*) AS total FROM tb_menu WHERE STOK <= 5")->fetch_assoc()['total'] ?? 0;
        $produk_habis = $conn->query("SELECT COUNT(*) AS total FROM tb_menu WHERE STOK = 0")->fetch_assoc()['total'] ?? 0;
        ?>
       <div class="stats-grid">
    <div class="stat-card card-total">
        <div class="icon-box">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <span>TOTAL PRODUK</span>
            <h2><?= $total_produk ?></h2>
        </div>
    </div>

    <div class="stat-card card-low">
        <div class="icon-box">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-info">
            <span>STOK RENDAH</span>
            <h2><?= $low_stock ?></h2>
        </div>
    </div>

    <div class="stat-card card-habis">
        <div class="icon-box">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-info">
            <span>PRODUK HABIS</span>
            <h2><?= $produk_habis ?></h2>
        </div>
    </div>
</div>
<div class="data-card">
    <div class="toolbar">
        <p>Daftar Menu</p>
    </div>

    <div class="grid-wrapper">
        <div class="grid-header">
            <div>Produk</div>
            <div>Kategori</div>
            <div>Harga</div>
            <div>Stok</div>
            <div>Rating</div>
            <div style="text-align: right;">Aksi</div>
        </div>

        <?php while ($menu = $query->fetch_assoc()): ?>
            <div class="grid-row">
                <div class="product-info">
                    <div class="img-placeholder">
                        <img src="../../source/gambar_menu/<?= htmlspecialchars($menu['FOTO_MENU']) ?>" width="45" height="45" style="border-radius:10px; object-fit:cover;">
                    </div>
                    <strong><?= htmlspecialchars($menu['NAMA_MENU']) ?></strong>
                </div>

                <div><?= htmlspecialchars($menu['KATEGORI']) ?></div>

                <div>Rp <?= number_format($menu['HARGA'], 0, ',', '.') ?></div>

                <div>
                    <?php if ((int)$menu['STOK'] <= 0 || $menu['STATUS'] === 'habis'): ?>
                        <span class="badge badge-red">Habis</span>
                    <?php else: ?>
                        <span class="badge badge-green"><?= (int)$menu['STOK'] ?> Tersedia</span>
                    <?php endif; ?>
                </div>

                <div style="display: flex; align-items: center; gap: 4px; color: #334155;">
                    <i class="fas fa-star" style="color: #face15;"></i> 
                    <span><?= number_format($menu['RATING'] ?? 0, 1) ?>/5</span>
                </div>

                <div class="actions" style="text-align: right;">
                    <span onclick="openEditModal(this)"
                        data-id="<?= $menu['ID_MENU'] ?>"
                        data-nama="<?= htmlspecialchars($menu['NAMA_MENU']) ?>"
                        data-kategori="<?= htmlspecialchars($menu['KATEGORI']) ?>"
                        data-harga="<?= $menu['HARGA'] ?>"
                        data-stok="<?= $menu['STOK'] ?>"
                        style="cursor: pointer; margin-right: 12px; color: #3b82f6;" 
                        title="Edit Menu">
                        <i class="fas fa-edit"></i>
                    </span>
                    
                    <span onclick="hapusMenuSoft(<?= $menu['ID_MENU'] ?>)" 
                        style="cursor: pointer; color: #ef4444;" 
                        title="Hapus Menu">
                        <i class="fas fa-trash-alt"></i>
                    </span>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
    </div>

    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fa-solid fa-pen-to-square"></i> Edit Data Produk</h2>
                <span class="close-btn" onclick="closeEditModal()">&times;</span>
            </div>

            <!-- action="./process/proses_edit_menu.php" method="POST" -->
            <form enctype="multipart/form-data" class="modal-body">
                <input type="hidden" name="id" id="edit_id_menu">

                <div class="form-group">
                    <label for="edit_nama">Nama Menu</label>
                    <input type="text" name="nama_menu" id="edit_nama" required>
                </div>

                <div class="form-group">
                    <label for="edit_kategori">Kategori</label>
                    <!-- <input type="text" name="kategori" id="edit_kategori" required> -->
                    <select name="kategori" id="edit_kategori">
                        <option value="makanan">makanan</option>
                        <option value="minuman">minuman</option>
                        <option value="snack">snack</option>
                    </select>
                </div>

          <div class="form-group">
    <label for="edit_harga">Harga (Rp)</label>
    <div class="input-counter-group">
        <button type="button" onclick="updateHarga(-500)">-500</button>
        <input type="number" name="harga" id="edit_harga" readonly>
        <button type="button" onclick="updateHarga(500)">+500</button>
    </div>
</div>

<div class="form-group">
    <label for="edit_stok">Jumlah Stok</label>
    <div class="input-counter-group">
        <button type="button" onclick="updateStock(-1)">-</button>
        <input type="number" name="stok" id="edit_stok" readonly>
        <button type="button" onclick="updateStock(1)">+</button>
    </div>
</div>

                <div class="form-group">
                    <label>Foto Menu (Kosongkan jika tidak diubah)</label>
                    <input type="hidden" name="type" value="photo-menu">
                    
                    <label for="edit_foto" class="custom-file-upload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> <span id="file-chosen">Pilih Foto Menu...</span>
                    </label>
                    
                    <input type="file" name="upfile" id="edit_foto" accept="image/*" style="display: none;" onchange="updateFileName(this)">
                </div>
                <button type="submit" class="btn-submit-modal">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
let hargaB;

function openEditModal(button) {
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    const kategori = button.getAttribute('data-kategori');
    const harga = button.getAttribute('data-harga');
    const stok = button.getAttribute('data-stok');

    document.getElementById('edit_id_menu').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_kategori').value = kategori;
    document.getElementById('edit_harga').value = harga;
    document.getElementById('edit_stok').value = stok;

    document.getElementById('editModal').classList.add('show');
    document.body.style.overflow = 'hidden'; 
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
    document.body.style.overflow = 'auto'; 
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
}

function updateFileName(input) {
    const fileNameDisplay = document.getElementById('file-chosen');
    if (input.files.length > 0) {
        fileNameDisplay.textContent = input.files[0].name;
    } else {
        fileNameDisplay.textContent = 'Pilih Foto Menu...';
    }
}

function updateHarga(input) {
    const harga = document.getElementById('edit_harga');
    var newVal = parseInt(harga.value) + input;

    if (newVal > 0) {
        harga.value = newVal;
    }
}

function updateStock(input) {
    const stock = document.getElementById('edit_stok');
    var newVal = parseInt(stock.value) + input;

    if (newVal > 0) {
        stock.value = newVal;
    }
}

document.querySelector('.modal-body').onsubmit = async (e) => {
    e.preventDefault();
    const formdata = new FormData(e.target);

    try {
        const response = await fetch('./process/pro_edit_menu.php', {
            method: 'POST',
            body: formdata
        });
        const result = await response.json();

        if (result.status == 'success') {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Menu berhasil diubah!',
                icon: 'success',
                confirmButtonColor: '#ff7e14',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                document.body.style.overflow = 'auto';
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: result.message,
                icon: 'error',
                confirmButtonColor: '#ff7e14'
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem saat menyimpan data.',
            icon: 'error',
            confirmButtonColor: '#ff7e14'
        });
    }
}

function hapusMenuSoft(idMenu) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Menu ini akan dihapus dari daftar aktif dashboard!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('aksi', 'soft_delete');
            formData.append('id_menu', idMenu);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Menu telah berhasil dihapus.',
                        icon: 'success',
                        confirmButtonColor: '#ff7e14',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan: ' + data.message,
                        icon: 'error',
                        confirmButtonColor: '#ff7e14'
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem atau jaringan.',
                    icon: 'error',
                    confirmButtonColor: '#ff7e14'
                });
            });
        }
    });
}
    </script>
</body>

</html>
