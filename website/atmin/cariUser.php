<?php
// Hubungkan ke file koneksi milikmu
require_once __DIR__ . "/../include/koneksi.php";

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Ambil data dari URL
$search_user = isset($_GET['query']) ? $_GET['query'] : '';
$user_results = [];

if (!empty($search_user)) {
    $keyword = "%" . $search_user . "%";

    // Query mengambil semua kolom kecuali PASS sesuai gambar
    // Kita cari berdasarkan USERNAME, NAMA_LENGKAP, atau ID
    $sql = "SELECT ID, USERNAME, NAMA_LENGKAP, NO_TLP, EMAIL, ROLE 
            FROM users 
            WHERE USERNAME LIKE ? OR NAMA_LENGKAP LIKE ? OR ID = ?";

    $stmt = $conn->prepare($sql);
    $id_search = is_numeric($search_user) ? intval($search_user) : 0;
    $stmt->bind_param("ssi", $keyword, $keyword, $id_search);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $user_results[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Data User</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 30px;
        }

        .container {
            background: white;
            max-width: 1100px;
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        h3 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #f8f9fa;
            color: #555;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 15px;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #444;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .btn-edit {
            background: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-edit:hover {
            background: #2980b9;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
        }

        .role-badge {
            background: #e1f5fe;
            color: #01579b;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <form action="" method="GET" class="input-group">
            <input type="text" name="query" placeholder="Ketik Username atau ID..." value="<?= htmlspecialchars($search_user) ?>">
            <button type="submit">Cari User</button>
        </form>
        <h3>Hasil Pencarian: "<?= htmlspecialchars($search_user) ?>"</h3>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>No. Telp</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($user_results) > 0): ?>
                    <?php foreach ($user_results as $row): ?>
                        <tr>
                            <td><?= $row['ID'] ?></td>
                            <td><strong><?= htmlspecialchars($row['USERNAME']) ?></strong></td>
                            <td><?= htmlspecialchars($row['NAMA_LENGKAP']) ?></td>
                            <td><?= htmlspecialchars($row['NO_TLP']) ?></td>
                            <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                            <td><span class="role-badge"><?= $row['ROLE'] ?></span></td>
                            <td style="text-align: center;">
                                <!-- Link edit mengarah ke file edit dengan membawa parameter ID -->
                                <a href="edit_user.php?id=<?= $row['ID'] ?>" class="btn-edit">EDIT</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:#999; padding: 30px;">Data user tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>