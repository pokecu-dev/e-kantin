<?php

require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/adminC.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
    header('Content-Type: application/json');
    try {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (empty($id) || !isset($status)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Parameter tidak lengkap!'
            ]);
            exit;
        }

        $sql = "UPDATE users SET STATUS = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        
        if($stmt->execute()){
            echo json_encode([
                'status' => 'success',
                'message' => 'Status berhasil diperbarui'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $stmt->error
            ]);
        }
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

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

$search_user = isset($_GET['query'])
    ? trim($_GET['query'])
    : '';

if ($search_user !== '') {

    $keyword = "%" . $search_user . "%";

    $sql = "SELECT * FROM users
            WHERE USERNAME LIKE ?
            OR NAMA_LENGKAP LIKE ?
            OR CAST(ID AS CHAR) LIKE ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param(
            "sss",
            $keyword,
            $keyword,
            $keyword
        );

        $stmt->execute();

        $query = $stmt->get_result();
    } else {

        die("Query error: " . $conn->error);
    }
} else {

    $sql = "SELECT * FROM users";

    $query = $conn->query($sql);
} ?>

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

            --col-id: 0.6fr;
            --col-username: 1fr;
            --col-name: 1.4fr;
            --col-phone: 1.1fr;
            --col-email: 1.6fr;
            --col-role: 0.8fr;
            --col-status: 0.9fr; 
            --col-action: 0.7fr;
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
            padding-right: 0px !important; 
        }

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

        .container {
            width: 100%;
            max-width: 1400px;
            margin-inline: auto;
            padding: 24px;
            box-sizing: border-box;
            margin-top: 60px;
        }

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

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .search-form {
            margin: 0;
        }

        .search-input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 0 18px;
            width: 300px;
            height: 52px;
        }

        .search-input-wrapper i {
            color: #94a3b8;
            font-size: 16px;
        }

        .search-input-wrapper input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 14px;
            background: transparent;
        }

        .btn-add {
            border: none;
            background: #f47b20;
            color: white;
            height: 52px;
            padding: 0 24px;
            border-radius: 999px;
            display: flex;
            align-items: center;
             max-width: 158px;
            gap: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .btn-add:hover {
            opacity: .9;
        }

        .search-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media(max-width:768px) {
            .top-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .top-actions {
                width: 100%;
            }
            .search-input-wrapper {
                width: 100%;
            }
            .btn-add {
                width: 100%;
           
                justify-content: center;
            }
        }

        .stats-grid {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            max-width: 100%;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .stats-grid::-webkit-scrollbar {
            display: none;
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

        .icon-total { background: #fff7ed; }
        .icon-low { background: #fef2f2; }
        .icon-active { background: #f0fdf4; }

        .stat-info span {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .stat-info h2 {
            font-size: 26px;
        }

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
            grid-template-columns: var(--col-id) var(--col-username) var(--col-name) var(--col-phone) var(--col-email) var(--col-role) var(--col-status) var(--col-action);
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

        .badge-active {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #b91c1c;
        }

        .user-table__link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--primary-orange);
            font-weight: 600;
        }

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

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-overlay.active {
            display: flex;
        }

        .close-modal {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }

        .modal-content {
            position: relative;
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 450px;
            max-width: 90%;
            height: auto;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .modal-content::-webkit-scrollbar {
            display: none;
        }

        .close-modal-right {
            position: absolute;
            top: 20px;
            right: 25px;
            background: none;
            border: none;
            font-size: 24px;
            font-weight: bold;
            color: #94a3b8;
            cursor: pointer;
            line-height: 1;
            padding: 5px;
            z-index: 10;
            transition: color 0.2s ease;
        }

        .close-modal-right:hover {
            color: #1e293b;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #cbd5e1;
            transition: .3s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        input:checked + .slider {
            background-color: #8fff5782;
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        /* MODAL KONFIRMASI KUSTOM (KantinKita-style) */
        .confirm-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-dark);
        }
        .confirm-text {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }
        .confirm-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .btn-confirm-cancel {
            background: #f1f5f9;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-confirm-cancel:hover {
            background: #e2e8f0;
        }
        .btn-confirm-ok {
            background: var(--primary-orange);
            border: none;
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-confirm-ok:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
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
                <li><a href="oulet.php">Kantin</a></li>
                <li><a href="./../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <div class="top-bar">
            <div class="header-title">
                <h1>Kelola User</h1>
                <p>Pantau dan atur semua akun pengguna dalam sistem.</p>
            </div>

            <div class="top-actions">
                <form action="" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <button type="submit" class="search-btn" style="background-color: #FEFEFE;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <input
                            type="text"
                            name="query"
                            placeholder="Cari user..."
                            value="<?= htmlspecialchars($search_user) ?>">
                    </div>
                </form>

                <button class="btn-add" onclick="openAddUserModal()">
                    <i class="fa-solid fa-plus"></i>
                    Tambah User
                </button>
            </div>
        </div>

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

        <div class="user-table">
            <div class="table-wrapper">
                <div class="user-table__header">
                    <div>Id</div>
                    <div>Username</div>
                    <div>Nama Lengkap</div>
                    <div>No Tlp</div>
                    <div>Email</div>
                    <div>Role</div>
                    <div>Status</div> <div>Aksi</div>
                </div>

                <?php if ($query && $query->num_rows > 0): ?>
                    <?php while ($user = $query->fetch_assoc()): ?>
                        <div class="user-table__row">
                            <div class="user-table__cell">#<?= htmlspecialchars($user['ID']) ?></div>
                            <div class="user-table__cell">
                                <strong><?= htmlspecialchars($user['USERNAME']) ?></strong>
                            </div>
                            <div class="user-table__cell"><?= htmlspecialchars($user['NAMA_LENGKAP']) ?></div>
                            <div class="user-table__cell"><?= htmlspecialchars($user['NO_TLP']) ?></div>
                            <div class="user-table__cell"><?= htmlspecialchars($user['EMAIL']) ?></div>
                            <div class="user-table__cell">
                                <span class="badge"><?= htmlspecialchars($user['ROLE']) ?></span>
                            </div>
                            
                            <div class="user-table__cell">
                                <label class="switch">
                                    <input 
                                        type="checkbox" 
                                        class="toggle-status" 
                                        data-id="<?= (int)$user['ID'] ?>" 
                                        data-username="<?= htmlspecialchars($user['USERNAME']) ?>"
                                        <?= (isset($user['STATUS']) && $user['STATUS'] == '1') ? 'checked' : '' ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="user-table__cell">
                                <button
                                    type="button"
                                    class="user-table__link"
                                    style="background:none;border:none;cursor:pointer;"
                                    onclick="openEditModal(<?= (int)$user['ID'] ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="padding:40px; text-align:center; color:#94a3b8; font-weight:600;">
                        User tidak ditemukan.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div id="addModal" class="modal-overlay">
        <div class="modal-content">
            </div>
    </div>

    <div id="editUserModal" class="modal-overlay">
        <div class="modal-content" id="modalBody">
            </div>
    </div>

    <div id="confirmStatusModal" class="modal-overlay">
        <div class="modal-content" style="width: 400px;">
            <div class="confirm-title">Konfirmasi Perubahan</div>
            <div class="confirm-text" id="confirmStatusMessage">Apakah Anda yakin ingin mengubah status user ini?</div>
            <div class="confirm-buttons">
                <button type="button" class="btn-confirm-cancel" id="btnConfirmCancel">Batal</button>
                <button type="button" class="btn-confirm-ok" id="btnConfirmOk">Yakin</button>
            </div>
        </div>
    </div>
<script src="./../shared/js/script.js"></script>

<script>
const editModal = document.getElementById("editUserModal");
const modalBody = document.getElementById("modalBody");

async function openEditModal(userId) {
    editModal.classList.add("active");
    modalBody.innerHTML = `
        <button type="button" class="close-modal-right" onclick="closeEditModal()">&times;</button>
        <div style="text-align:center;padding:30px;">
            <i class="fa-solid fa-spinner fa-spin" style="font-size:30px;color:#f36f21;"></i>
            <p style="margin-top:10px;">Memuat data...</p>
        </div>
    `;

    try {
        const response = await fetch(`edituser.php?id=${userId}`);
        if (!response.ok) throw new Error("Fetch gagal");
        const html = await response.text();
        modalBody.innerHTML = `
            <button type="button" class="close-modal-right" onclick="closeEditModal()">&times;</button>
            <div style="margin-top:10px;">${html}</div>
        `;

    } catch (error) {
        modalBody.innerHTML = `
            <button type="button" class="close-modal-right" onclick="closeEditModal()">&times;</button>
            <div style="padding:30px;text-align:center;">
                <i class="fa-solid fa-circle-exclamation" style="font-size:30px;color:red;"></i>
                <p style="margin-top:10px;">Gagal memuat data user.</p>
            </div>
        `;
    }
}

function closeEditModal() { 
    editModal.classList.remove("active"); 
    setTimeout(() => { modalBody.innerHTML = ''; }, 200);
}

editModal.addEventListener("click", (e) => {
    if (e.target === editModal) {
        closeEditModal();
    }
});

const addModal = document.getElementById("addModal");
const addModalContent = addModal.querySelector(".modal-content");

function openAddUserModal() {
    addModal.classList.add("active");
    resetAddMenu();
}

function closeAddModal() {
    addModal.classList.remove("active");
    setTimeout(() => { resetAddMenu(); }, 200);
}

addModal.addEventListener("click", (e) => {
    if (e.target === addModal) {
        closeAddModal();
    }
});

function resetAddMenu() {
    addModalContent.innerHTML = `
        <button class="close-modal" onclick="closeAddModal()">&times;</button>
        <h3 style="margin-bottom:15px; text-align:center;">Pilih Jenis Akun</h3>
        <button class="btn-action" style="width:100%; margin-bottom:10px;" onclick="loadForm('addPembeli.php')">Tambah Pembeli</button>
        <button class="btn-action" style="width:100%; margin-bottom:10px;" onclick="loadForm('addPenjual.php')">Tambah Penjual</button>
        <button class="btn-action" style="width:100%;" onclick="loadForm('addAdmin.php')">Tambah Admin</button>
    `;
}

async function loadForm(file) {
    addModalContent.innerHTML = `
        <button type="button" class="close-modal-right" onclick="closeAddModal()">&times;</button>
        <div style="text-align:center;padding:30px;">
            <i class="fa-solid fa-spinner fa-spin" style="font-size:30px;color:#f36f21;"></i>
            <p style="margin-top:10px;">Memuat form...</p>
        </div>
    `;
    try {
        const response = await fetch(file);
        if (!response.ok) throw new Error("Gagal load form");
        const html = await response.text();
        addModalContent.innerHTML = `
            <button type="button" class="close-modal-right" onclick="closeAddModal()">&times;</button>
            <div style="margin-top:10px;">${html}</div>
        `;
    } catch (error) {
        addModalContent.innerHTML = `
            <button type="button" class="close-modal-right" onclick="closeAddModal()">&times;</button>
            <p style="color:red;text-align:center;padding:20px;">Gagal memuat form.</p>
        `;
    }
}

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        closeEditModal();
        closeAddModal();
        closeConfirmModal();
    }
});

document.addEventListener('submit', (e) => {
    if (e.target.closest('#modalBody') || e.target.closest('.modal-content')) {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
});

document.addEventListener('input', function(e) {
    if (e.target.id === 'no_tlp' && e.target.closest('#modalBody')) {
        let value = e.target.value;
        let numbers = value.replace(/\D/g, '');
        let formatted = '+';
        if (numbers.length > 0) formatted += numbers.substring(0, 2);
        if (numbers.length > 2) formatted += ' ' + numbers.substring(2, 5);
        if (numbers.length > 5) formatted += ' ' + numbers.substring(5, 9);
        if (numbers.length > 9) formatted += ' ' + numbers.substring(9, 13);
        e.target.value = formatted;
    }
});

/* FIX: LOGIKA MODAL PERUBAHAN STATUS MENGGUNAKAN EVENT DELEGATION */
const confirmModal = document.getElementById("confirmStatusModal");
const confirmMessage = document.getElementById("confirmStatusMessage");
const btnConfirmOk = document.getElementById("btnConfirmOk");
const btnConfirmCancel = document.getElementById("btnConfirmCancel");

let targetCheckbox = null; // Menyimpan elemen checkbox yang sedang diklik
let targetAkanAktif = false; // Menyimpan status masa depan yang diinginkan

// Dengerin event 'change' di seluruh dokumen (Event Delegation)
document.addEventListener('change', function(e) {
    if (e.target && e.target.classList.contains('toggle-status')) {
        targetCheckbox = e.target;
        targetAkanAktif = targetCheckbox.checked; 
        
        // Kembalikan dulu visual switch-nya ke posisi semula sebelum di-approve user via Modal
        targetCheckbox.checked = !targetAkanAktif;

        const username = targetCheckbox.getAttribute('data-username');
        const statusTeks = targetAkanAktif ? 'mengaktifkan' : 'menonaktifkan';
        
        confirmMessage.innerHTML = `Apakah Anda yakin ingin <strong>${statusTeks}</strong> akun milik <strong>${username}</strong>?`;
        confirmModal.classList.add("active");
    }
});

// Jika User menekan tombol Batal
btnConfirmCancel.addEventListener('click', closeConfirmModal);

confirmModal.addEventListener('click', (e) => {
    if (e.target === confirmModal) {
        closeConfirmModal();
    }
});

function closeConfirmModal() {
    confirmModal.classList.remove("active");
    targetCheckbox = null;
}

// Jika User menekan tombol Yakin
btnConfirmOk.addEventListener('click', () => {
    if (!targetCheckbox) return;

    const userId = targetCheckbox.getAttribute('data-id');
    const statusNilai = targetAkanAktif ? '1' : '0';
    
    const formData = new FormData();
    formData.append('toggle_status', '1');
    formData.append('id', userId);
    formData.append('status', statusNilai);
    
    confirmModal.classList.remove("active");

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Animasi toggle bergerak ke posisi baru hanya jika sukses di database
            targetCheckbox.checked = targetAkanAktif;
        } else {
            alert('Gagal mengubah status: ' + data.message);
            targetCheckbox.checked = !targetAkanAktif;
        }
        targetCheckbox = null;
    })
    .catch(error => {
        alert('Terjadi kesalahan koneksi sistem.');
        console.error(error);
        targetCheckbox.checked = !targetAkanAktif;
        targetCheckbox = null;
    });
});
</script>
</body>
</html>