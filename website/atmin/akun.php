<?php

require_once __DIR__ . "/../include/koneksi.php";
// require_once __DIR__ . "/../include/session/loginCheck.php";

if ($conn->error) {
    echo $conn->connect_error;
}

$sql = "SELECT * FROM users";
$query = $conn->query($sql);
$totalPembeli = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM users WHERE ROLE='PEMBELI'"
    )
);

$totalPenjual = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM users WHERE ROLE='PENJUAL'"
    )
);

$totalAdmin = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM users WHERE ROLE='ADMIN'"
    )
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>

    <link rel="stylesheet" href="style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-y: scroll;

        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gray);
            color: var(--text-dark);
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* NAVBAR STYLE - TIDAK DIUBAH */
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

        /* =====================
           CONTAINER
        ===================== */
        .container {
            width: 100%;
            max-width: 1400px;
            margin-inline: auto;
            padding: 24px;
            box-sizing: border-box;
            margin-top: 60px;
        }

        /* =====================
           HEADER
        ===================== */
        .header {
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            margin-bottom: 10px;
        }

        .header-title p {
            color: var(--text-muted);
        }

        /* =====================
           SEARCH BOX
        ===================== */
        
 .top-bar{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    margin-bottom:30px;
    flex-wrap:wrap;
}

.top-actions{
    display:flex;
    align-items:center;
    gap:16px;
    margin-left:auto;
}

.search-form{
    margin:0;
}

.search-input-wrapper{
    display:flex;
    align-items:center;
    gap:12px;

    background:white;

    border:1px solid #e2e8f0;
    border-radius:999px;

    padding:0 18px;

    width:300px;
    height:52px;
}

.search-input-wrapper i{
    color:#94a3b8;
    font-size:16px;
}

.search-input-wrapper input{
    border:none;
    outline:none;
    width:100%;
    font-size:14px;
    background:transparent;
}

.btn-add{
    border:none;
    background:#f47b20;
    color:white;

    height:52px;
    padding:0 24px;

    border-radius:999px;

    display:flex;
    align-items:center;
    gap:10px;

    font-weight:600;
    cursor:pointer;

    transition:.2s;
}

.btn-add:hover{
    opacity:.9;
}

@media(max-width:768px){

    .top-bar{
        flex-direction:column;
        align-items:stretch;
    }

    .top-actions{
        width:100%;
    }

    .search-input-wrapper{
        width:100%;
    }

    .btn-add{
        width:100%;
        justify-content:center;
    }

}
        /* =====================
           STATS
        ===================== */

        .stats-grid {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            max-width: 100%;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 18px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-soft);
           
            width: 32%;
            overflow: hidden;
            min-width: 260px;
            flex-shrink: 0;
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

        .icon-total {
            background: #fff7ed;
        }

        .icon-low {
            background: #fef2f2;
        }

        .icon-active {
            background: #f0fdf4;
        }

        .stat-info span {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .stat-info h2 {
            font-size: 26px;
        }

        /* =====================
           ACTION BAR
        ===================== */
       

        .btn-action {
            background: white;
            border: 1px solid var(--border-color);
            padding: 12px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
        }

        .btn-action:hover {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
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
            .container {
                padding: 16px;
            }

            .action-bar {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                text-align: center;
            }

            .search-wrapper {
                padding: 14px;
            }

            .stat-card {
                padding: 18px;
            }
        }

        /* =====================
           MODAL DIALOG DIOPTIMALKAN
        ===================== */
        #editUserModal {
            border: none;
            margin: auto;
            width: min(450px, 90vw);
            max-height: 85vh;
            border-radius: 20px;
            padding: 24px;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        #editUserModal::-webkit-scrollbar {
            display: none;

        }

        #editUserModal::backdrop {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            /* Efek blur modern pada latar belakang modal */
            transition: all 0.3s ease;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 24px;
            border-radius: 16px;
            width: 420px;
            max-width: 90%;
        }

        .close-modal {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <!-- NAVBAR (UNTOUCHED) -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"> <img src="../../source/icon/logo1.svg" alt=""></div>
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <ul class="nav-links">
                <li><a href="admin.php">Beranda</a></li>
                <li><a href="akun.php" class="active">Akun</a></li>
                <li><a href="menu.php">Produk</a></li>
                <li><a href="oulet.php">Outlet</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <main class="container">
      <div class="top-bar">

    <div class="header-title">
        <h1>Kelola User</h1>
        <p>Pantau dan atur semua akun pengguna dalam sistem.</p>
    </div>

    <div class="top-actions">

        <form action="cariUser.php" method="GET" class="search-form">

            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input 
                    type="text" 
                    name="query" 
                    placeholder="Cari outlet..." 
                    required>
            </div>

        </form>

        <button class="btn-add" onclick="openAddUserModal()">
            <i class="fa-solid fa-plus"></i>
            Tambah Outlet
        </button>

    </div>

</div>

        <!-- STATS -->
       <div class="stats-grid">

    <div class="stat-card">
        <div class="icon-box icon-total">📦</div>

        <div class="stat-info">
            <span>TOTAL PEMBELI</span>
            <h2><?= $totalPembeli ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon-box icon-low">⚠️</div>

        <div class="stat-info">
            <span>TOTAL PENJUAL</span>
            <h2><?= $totalPenjual ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon-box icon-active">✅</div>

        <div class="stat-info">
            <span>TOTAL ADMIN</span>
            <h2><?= $totalAdmin ?></h2>
        </div>
    </div>

</div>
        <!-- USER TABLE (GRID UNTOUCHED) -->
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

                <?php while ($user = $query->fetch_assoc()): ?>
                    <div class="user-table__row">
                        <div class="user-table__cell">
                            <strong><?= htmlspecialchars($user['USERNAME']) ?></strong>
                        </div>
                        <div class="user-table__cell">
                            <?= htmlspecialchars($user['NAMA_LENGKAP']) ?>
                        </div>
                        <div class="user-table__cell">
                            <?= htmlspecialchars($user['NO_TLP']) ?>
                        </div>
                        <div class="user-table__cell">
                            <?= htmlspecialchars($user['EMAIL']) ?>
                        </div>
                        <div class="user-table__cell">
                            <span class="badge"><?= htmlspecialchars($user['ROLE']) ?></span>
                        </div>
                        <div class="user-table__cell">
                            <button type="button"
                                class="user-table__link"
                                style="background: none; border: none; cursor: pointer;"
                                onclick="openEditModal(<?= (int)$user['ID'] ?>)">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <div id="addModal" class="modal-overlay">
        <div class="modal-content">

            <span class="close-modal" onclick="closeAddModal()">&times;</span>

            <h3 style="margin-bottom:15px;">Pilih Jenis Akun</h3>

            <button class="btn-action" style="width:100%; margin-bottom:10px;"
                onclick="loadForm('addPembeli.php')">
                Tambah Pembeli
            </button>

            <button class="btn-action" style="width:100%; margin-bottom:10px;"
                onclick="loadForm('addPenjual.php')">
                Tambah Penjual
            </button>

            <button class="btn-action" style="width:100%;"
                onclick="loadForm('addAdmin.php')">
                Tambah Admin
            </button>

            <hr style="margin:15px 0;">

            <div id="formArea">
                <p style="text-align:center; color:#888;">
                    Pilih salah satu dulu
                </p>
            </div>

        </div>
    </div>

    <!-- POIN UTAMA PERBAIKAN: STRUKTUR KODE DIALOG MODAL YANG SEBELUMNYA HILANG -->
    <dialog id="editUserModal">
        <div id="modalBody">
            <!-- Data dari AJAX edituser.php akan di-render di sini -->
        </div>
    </dialog>

    <script>
        // =========================
        // MODAL EDIT USER
        // =========================

        const modal = document.getElementById('editUserModal');
        const modalBody = document.getElementById('modalBody');

        async function openEditModal(userId) {

            modalBody.innerHTML = `
            <div style="text-align:center; padding: 30px;">
                <i class="fa-solid fa-spinner fa-spin"
                style="font-size:30px;color:#f36f21;"></i>

                <p style="margin-top:10px;">
                    Memuat data...
                </p>
            </div>
        `;

            modal.showModal();

            try {

                const response = await fetch(`edituser.php?id=${userId}`);

                if (!response.ok) {
                    throw new Error('Fetch gagal');
                }

                const html = await response.text();

                modalBody.innerHTML = html;

            } catch (error) {

                modalBody.innerHTML = `
                <div style="padding:30px;text-align:center;">
                    <i class="fa-solid fa-circle-exclamation"
                    style="font-size:30px;color:red;"></i>

                    <p style="margin-top:10px;">
                        Gagal memuat data user.
                    </p>

                    <button
                        onclick="closeEditModal()"
                        class="btn-orange"
                        style="margin-top:15px;">
                        Tutup
                    </button>
                </div>
            `;
            }
        }

        function closeEditModal() {
            modal.close();
        }

        modal.addEventListener('click', (e) => {

            const rect = modal.getBoundingClientRect();

            const isOutside =
                e.clientX < rect.left ||
                e.clientX > rect.right ||
                e.clientY < rect.top ||
                e.clientY > rect.bottom;

            if (isOutside) {
                closeEditModal();
            }
        });

        document.addEventListener('keydown', (e) => {

            if (e.key === 'Escape') {
                closeEditModal();
                closeAddModal();
            }
        });

        // =========================
        // MODAL TAMBAH USER
        // =========================

        function openAddUserModal() {

            document.getElementById("addModal")
                .classList.add("active");

            backToMenu();
        }

        function closeAddModal() {

            document.getElementById("addModal")
                .classList.remove("active");
        }

        async function loadForm(file) {

            const modalContent =
                document.querySelector(".modal-content");

            modalContent.innerHTML = `
            <span class="close-modal"
                onclick="closeAddModal()">
                &times;
            </span>

            <button
                onclick="backToMenu()"
                class="btn-action"
                style="margin-bottom:20px;">
                ← Kembali
            </button>

            <div style="text-align:center; padding:30px;">
                <i class="fa-solid fa-spinner fa-spin"
                style="font-size:30px;color:#f36f21;"></i>

                <p style="margin-top:10px;">
                    Memuat form...
                </p>
            </div>
        `;

            try {

                const response = await fetch(file);

                if (!response.ok) {
                    throw new Error("Gagal load form");
                }

                const html = await response.text();

                modalContent.innerHTML = `
                <span class="close-modal"
                    onclick="closeAddModal()">
                    &times;
                </span>

                <button
                    onclick="backToMenu()"
                    class="btn-action"
                    style="margin-bottom:20px;">
                    ← Kembali
                </button>

                ${html}
            `;

            } catch (error) {

                modalContent.innerHTML = `
                <span class="close-modal"
                    onclick="closeAddModal()">
                    &times;
                </span>

                <p style="color:red; text-align:center;">
                    Gagal memuat form.
                </p>
            `;
            }
        }

        function backToMenu() {

            const modalContent =
                document.querySelector(".modal-content");

            modalContent.innerHTML = `
            <span class="close-modal"
                onclick="closeAddModal()">
                &times;
            </span>

            <h3 style="margin-bottom:15px;">
                Pilih Jenis Akun
            </h3>

            <button
                class="btn-action"
                style="width:100%; margin-bottom:10px;"
                onclick="loadForm('addPembeli.php')">

                Tambah Pembeli
            </button>

            <button
                class="btn-action"
                style="width:100%; margin-bottom:10px;"
                onclick="loadForm('addPenjual.php')">

                Tambah Penjual
            </button>

            <button
                class="btn-action"
                style="width:100%;"
                onclick="loadForm('addAdmin.php')">

                Tambah Admin
            </button>
        `;
        }
    </script>
</body>

</html>