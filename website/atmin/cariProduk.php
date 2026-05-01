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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Menu Kantin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; padding: 20px; }
        .container { background: white; max-width: 1300px; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h3 { border-bottom: 2px solid #e67e22; padding-bottom: 10px; color: #333; margin-top: 20px; }
        
        /* Style untuk Form Pencarian di dalam halaman */
        .input-group { display: flex; gap: 10px; margin-bottom: 20px; background: #fff3e0; padding: 15px; border-radius: 8px; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-orange { background: #e67e22; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-orange:hover { background: #d35400; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; }
        th { background: #f1f3f5; color: #495057; padding: 12px; text-align: left; font-size: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 13px; vertical-align: top; }
        .img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
        .status-badge { padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .tersedia { background: #d4edda; color: #155724; }
        .habis { background: #f8d7da; color: #721c24; }
        .btn-edit { background: #e67e22; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 12px; }
        .btn-edit:hover { background: #d35400; }
        .deskripsi { max-width: 200px; color: #666; font-size: 12px; line-height: 1.4; }
    </style>
</head>
<body>

    <div class="container">
        <!-- FORM PENCARIAN ULANG -->
        <form action="" method="GET" class="input-group">
            <input type="text" name="query" placeholder="Nama Menu, ID Menu, atau ID Kantin..." value="<?= htmlspecialchars($search_query) ?>" required>
            <button type="submit" class="btn-orange">Cari Menu</button>
        </form>

        <a href="cari_menu.php" style="text-decoration:none; color:#e67e22; font-size:14px;">&larr; Kembali </a>
        
        <h3>Hasil Pencarian: "<?= htmlspecialchars($search_query) ?>"</h3>

        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>ID</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($search_query) && count($menu_results) > 0): ?>
                    <?php foreach ($menu_results as $row): ?>
                        <tr>
                           <td>
    <?php if(!empty($row['FOTO_MENU'])): ?>
      
        <img src="../../source/gambar_menu/<?= $row['FOTO_MENU'] ?>" class="img-preview" alt="foto">
    <?php else: ?>
        <div style="width:60px;height:60px;background:#eee;display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No Pict</div>
    <?php endif; ?>
</td>
                            <td>
                                <small>Menu: #<?= $row['ID_MENU'] ?></small><br>
                                <small>Kantin: K-<?= $row['ID_KANTIN'] ?></small>
                            </td>
                            <td><strong><?= htmlspecialchars($row['NAMA_MENU']) ?></strong></td>
                            <td>Rp <?= number_format($row['HARGA'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['KATEGORI']) ?></td>
                            <td><?= $row['STOK'] ?></td>
                            <td>
                                <span class="status-badge <?= (strtolower($row['STATUS']) == 'tersedia') ? 'tersedia' : 'habis' ?>">
                                    <?= htmlspecialchars($row['STATUS']) ?>
                                </span>
                            </td>
                            <td class="deskripsi"><?= htmlspecialchars($row['DESK']) ?></td>
                            <td style="text-align: center;">
                                <a href="edit_menu.php?id=<?= $row['ID_MENU'] ?>" class="btn-edit">EDIT</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center; color:#999; padding: 30px;">
                            <?= empty($search_query) ? "Silakan masukkan kata kunci pencarian." : "Menu tidak ditemukan." ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>