<?php
// Hubungkan ke file koneksi milikmu
require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/adminC.php";

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
          :root {
            --primary-orange: #f36f21;
            --bg-gray: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --white: #ffffff;

            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.05);
            --radius: 18px;

            /* GRID UTAMA - TIDAK DIUBAH SAMA SEKALI */
            --col-username: 1fr;
            --col-name: 1.5fr;
            --col-phone: 1.2fr;
            --col-email: 1.8fr;
            --col-role: .9fr;
            --col-action: .8fr;
        }

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

         .search-box {
            margin-bottom: 30px;
        }

        .search-wrapper {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: var(--radius);
            padding: 18px;
        }

        .search-wrapper h2 {
            margin-bottom: 16px;
            font-size: 18px;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .search-form input {
            flex: 1 1 300px;
            min-width: 0;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            outline: none;
            transition: border-color 0.2s ease;
        }

        .search-form input:focus {
            border-color: var(--primary-orange);
        }

        .btn-orange {
            border: none;
            background: var(--primary-orange);
            color: white;
            padding: 14px 22px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .btn-orange:hover {
            background: #d95f14;
        }
  /* =====================
           USER TABLE & GRID - RESERVED & UNTOUCHED
        ===================== */
        .user-table {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-soft);
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }


        .user-table__header,
        .user-table__row {
            min-width: 1100px;
     
            display: grid;
            grid-template-columns: var(--col-username) var(--col-name) var(--col-phone) var(--col-email) var(--col-role) var(--col-action);
            gap: 16px;
            align-items: center;
            padding: 18px 20px;
        }

        .user-table__header {
            background: #fafafa;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
        }

        .user-table__row {
            border-bottom: 1px solid var(--border-color);
            transition: .2s;
        }

        .user-table__row:hover {
            background: #f8fafc;
        }

        .user-table__cell {
            font-size: 14px;
            word-break: break-word;
        }

        .user-table__cell strong {
            color: var(--text-dark);
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #ffedd5;
            color: #ea580c;
        }

        .user-table__link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--primary-orange);
            font-weight: 600;
        }

        /* =====================
           MOBILE RESPONSIVE
        ===================== */
        @media (max-width: 768px) {
            .container { padding: 16px; }
            .action-bar { flex-direction: column; }
            .btn-action { width: 100%; text-align: center; }
            .search-wrapper { padding: 14px; }
            .stat-card { padding: 18px; }
        }

    </style>
</head>

<body>

    <div class="container">
          <!-- SEARCH -->
        <div class="search-box">
            <div class="search-wrapper">
                <form action="" method="GET" class="search-form">
                    <input type="text" name="query" placeholder="Masukkan Username atau ID..." required>
                    <button type="submit" class="btn-orange">Cari Sekarang</button>
                </form>
            </div>
        </div>
        <h3>Hasil Pencarian: "<?= htmlspecialchars($search_user) ?>"</h3>


         <div class="user-table">
            <div class="table-wrapper">
                <div class="user-table__header">
                    <div>Username</div>
                    <div>Nama Lengkap</div>
                    <div>No Tlp</div>
                    <div>Email</div>
                    <div>Role</div>
                    <div>Aksi</div>
                </div>

                    <?php if (count($user_results) > 0): ?>
                        <?php foreach ($user_results as $row): ?>
                            <div class="user-table__row">
                            
                                <p class="user-table__cell"><strong><?= htmlspecialchars($row['USERNAME']) ?></strong></p>
                                <p class="user-table__cell"><?= htmlspecialchars($row['NAMA_LENGKAP']) ?></p>
                                <p class="user-table__cell"><?= htmlspecialchars($row['NO_TLP']) ?></p>
                                <p class="user-table__cell"><?= htmlspecialchars($row['EMAIL']) ?></p>
                                <p class="user-table__cell"><span class="role-badge"><?= $row['ROLE'] ?></span></p>
                                <p style="text-align: center;" lass="user-table__cell">>
                                    <!-- <-- Link edit mengarah ke file edit dengan membawa parameter ID -->
                                    <a href="edituser.php?id=<?= $row['ID'] ?>" class="btn-edit">EDIT</a>
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

</body>

</html>