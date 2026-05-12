<?php
// Hubungkan ke file koneksi milikmu
require_once __DIR__ . "/../include/koneksi.php";

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Ambil data dari URL
// Gunakan 'query' agar konsisten dengan input name="query"
$search_query = isset($_GET['query']) ? $_GET['query'] : '';
$menu_results = [];

if (!empty($search_query)) {
    $keyword = "%" . $search_query . "%";

    // Query sesuai struktur kolom kapital di database kamu
    $sql = "SELECT ID_MENU, ID_KANTIN, NAMA_MENU, HARGA, KATEGORI, STOK, STATUS, FOTO_MENU, DESK 
            FROM tb_menu 
            WHERE NAMA_MENU LIKE ? OR KATEGORI LIKE ? OR ID_MENU = ? OR ID_KANTIN = ?";

    $stmt = $conn->prepare($sql);
    $id_search = is_numeric($search_query) ? intval($search_query) : 0;
    $stmt->bind_param("ssii", $keyword, $keyword, $id_search, $id_search);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $menu_results[] = $row;
    }
    $stmt->close();
}
?>


<body>

    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Menu Kantin</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }

        .container {
            background: white;
            max-width: 1300px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* --- Style Grid Tabel --- */
        .parent-tabel {
            background-color: #dac8b9;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .card-overflow {
            overflow-x: auto;
        }

        .header-grid, .baris-grid {
            display: grid;
            /* 9 Kolom: Disesuaikan lebarnya */
            grid-template-columns: 80px 100px 1.5fr 1fr 1fr 60px 100px 2fr 80px;
            gap: 10px;
            padding: 12px;
            align-items: center;
            min-width: 1000px; /* Agar tidak hancur di layar kecil saat scroll */
        }

        .header-grid {
            background: #fff5eb;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 8px;
            border-bottom: 2px solid #492509;
            text-transform: uppercase;
            font-size: 12px;
        }

        .baris-grid {
            background: white;
            border-bottom: 1px solid #eee;
            margin-bottom: 4px;
            border-radius: 4px;
            transition: 0.3s;
        }

        .baris-grid:hover {
            background: #fdfaf7;
        }

        .baris-grid p {
            margin: 0;
            font-size: 13px;
            word-break: break-word;
        }

        /* --- Komponen Pendukung --- */
        .img-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }

        .tersedia { background: #d4edda; color: #155724; }
        .habis { background: #f8d7da; color: #721c24; }

        .btn-edit {
            background: #e67e22;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }

        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            background: #fff3e0;
            padding: 15px;
            border-radius: 8px;
        }

        input[type="text"] {
            flex: 1; padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-orange {
            background: #e67e22; color: white;
            border: none; padding: 10px 20px;
            border-radius: 5px; cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <form action="" method="GET" class="input-group">
        <input type="text" name="query" placeholder="Cari Nama, ID Menu, atau ID Kantin..." value="<?= htmlspecialchars($search_query) ?>" required>
        <button type="submit" class="btn-orange">Cari Menu</button>
    </form>

    <a href="cari_menu.php" style="text-decoration:none; color:#e67e22;">&larr; Kembali</a>

    <h3>Hasil Pencarian: "<?= htmlspecialchars($search_query) ?>"</h3>

    <div class="parent-tabel">
        <div class="card-overflow">
            <!-- Header Tabel ala Grid -->
            <div class="header-grid">
                <p>Foto</p>
                <p>ID</p>
                <p>Nama Menu</p>
                <p>Harga</p>
                <p>Kategori</p>
                <p>Stok</p>
                <p>Status</p>
                <p>Deskripsi</p>
                <p style="text-align:center;">Aksi</p>
            </div>

            <?php if (!empty($search_query) && count($menu_results) > 0): ?>
                <?php foreach ($menu_results as $row): ?>
                    <div class="baris-grid">
                        <!-- Kolom Foto -->
                        <div>
                            <?php if (!empty($row['FOTO_MENU'])): ?>
                                <img src="../../source/gambar_menu/<?= $row['FOTO_MENU'] ?>" class="img-preview" alt="foto">
                            <?php else: ?>
                                <div style="width:60px;height:60px;background:#eee;display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No Pict</div>
                            <?php endif; ?>
                        </div>

                        <!-- Kolom ID -->
                        <div>
                            <p>M: #<?= $row['ID_MENU'] ?></p>
                            <p style="color:#777; font-size:11px;">K: <?= $row['ID_KANTIN'] ?></p>
                        </div>

                        <!-- Kolom Nama -->
                        <p><strong><?= htmlspecialchars($row['NAMA_MENU']) ?></strong></p>

                        <!-- Kolom Harga -->
                        <p>Rp <?= number_format($row['HARGA'], 0, ',', '.') ?></p>

                        <!-- Kolom Kategori -->
                        <p><?= htmlspecialchars($row['KATEGORI']) ?></p>

                        <!-- Kolom Stok -->
                        <p><?= $row['STOK'] ?></p>

                        <!-- Kolom Status -->
                        <div>
                            <span class="status-badge <?= (strtolower($row['STATUS']) == 'tersedia') ? 'tersedia' : 'habis' ?>">
                                <?= htmlspecialchars($row['STATUS']) ?>
                            </span>
                        </div>

                        <!-- Kolom Deskripsi -->
                        <p style="color:#666; font-size:12px;"><?= htmlspecialchars($row['DESK']) ?></p>

                        <!-- Kolom Aksi -->
                        <div style="text-align: center;">
                            <a href="edit_menu.php?id=<?= $row['ID_MENU'] ?>" class="btn-edit">EDIT</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center; background:white; padding:30px; border-radius:5px; color:#999;">
                    <?= empty($search_query) ? "Silakan masukkan kata kunci pencarian." : "Menu tidak ditemukan." ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>