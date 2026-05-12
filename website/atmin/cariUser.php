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
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data User</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 30px;
        }

        /* .container {
            background: white;
            max-width: 1100px;
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        } */

        h3 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .header-tabel,
        .div1 {
            display: grid;
            /* 4 Kolom: kolom pertama lebih lebar (2fr), sisanya sama rata (1fr) */
            grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 1fr 1fr;
            gap: 10px;
            padding: 8px;
            min-width: 700px;
            border-bottom: 1px solid #492509;

            align-items: start;
        }

        .parent {
            background-color: #dac8b9;
            padding: 15px;
            border-radius: 10px;

        }

        .header-tabel {
            background: #fff5eb;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        p {
            font-size: small;
        }

        .div1 {
            line-height: 1.4;
            max-height: fit-content;
        }

        .div1 p {
            word-break: break-word;
            margin: 0;
        }
        .card2 {
        overflow-x: auto;
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
            color: #e49408;
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


        <div class="parent">
            <div class="card2">
                <div class="header-tabel">
                    <p>ID</p>
                    <p>USERNAME</p>
                    <p>NAMA LENGKAP</p>
                    <p>NO TLP</p>
                    <p>EMAIL</p>
                    <p>ROLE</p>
                    <p>AKSI</p>
                </div>

                <div class="card">
                    <?php if (count($user_results) > 0): ?>
                        <?php foreach ($user_results as $row): ?>
                            <div class="div1">
                                <p><?= $row['ID'] ?></td>
                                <p><strong><?= htmlspecialchars($row['USERNAME']) ?></strong></p>
                                <p><?= htmlspecialchars($row['NAMA_LENGKAP']) ?></p>
                                <p><?= htmlspecialchars($row['NO_TLP']) ?></p>
                                <p><?= htmlspecialchars($row['EMAIL']) ?></p>
                                <p><span class="role-badge"><?= $row['ROLE'] ?></span></p>
                                <p style="text-align: center;">
                                    <!-- <-- Link edit mengarah ke file edit dengan membawa parameter ID -->
                                    <a href="edit_user.php?id=<?= $row['ID'] ?>" class="btn-edit">EDIT</a>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div>
                            <p style="text-align:center; color:#999; padding: 30px;">Data user tidak ditemukan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

</body>

</html>