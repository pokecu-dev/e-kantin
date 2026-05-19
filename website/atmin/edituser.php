<?php
require_once __DIR__ . "/../include/koneksi.php";
session_start();
if ($_SESSION['role'] != 'ADMIN') {
    header('Location: ../login.php');
    exit;
}


$dataUsers = [
    'NAMA_LENGKAP' => '',
    'USERNAME' => '',
    'ROLE' => '',
    'FOTO_USERS' => '', // sesuaikan dengan file default kamu
    'PASS' => '',
    'NO_TLP' => '',
    'EMAIL' => ''
];

// $id = $conn->real_escape_string($_GET['id']);
$id = $_GET['id'];
$id = $conn->real_escape_string($id);
$sql = "select * from users where ID='$id'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dataUsers = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - <?= $dataUsers['NAMA_LENGKAP'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #ffffff;
            --bg-card: #fcfcfc;
            /* Putih Pucat */
            --primary: #F47B20;
            --primary-hover: #f0781c;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-md: 0 4px 12px -2px rgba(0, 0, 0, 0.08);
            /* Bayangan Halus */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            /* background-color: var(--bg-body);
            color: var(--text-main);
           
            line-height: 1.5; */
        }

    /* width: 100%;
    max-width: 1400px;
    margin-inline: auto;
    padding: 24px;
    box-sizing: border-box;
    margin-top: 60px; */

.main-grid {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin: auto;
}

        .category-title {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary);
            margin-bottom: 10px;
            padding-left: 5px;
        }

        .card {
            background-color: var(--bg-card);
            padding: 25px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            margin-bottom: 25px;
        }

        .profile-card {
            text-align: center;
        }

        .foto-container {
            width: 130px;
            height: 130px;
            margin: 0 auto 15px;
        }

        .foto {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: var(--shadow-md);
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #f6ebe3;
            color: var(--primary);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        label span {
            display: none;
            color: #94a3b8;
            font-weight: 400;
            font-size: 0.75rem;
        }

        /* Style untuk tulisan BEFORE */

        input,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background-color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(244, 123, 32, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        @media (max-width: 900px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="main">
        <form data-ajax="true" data-action="./process/pro_edit.php" data-notif="notif">
            <div class="main-grid">

                <!-- KOLOM KIRI -->
                <div class="left-column">
                    <span class="category-title">Identitas</span>
                    <div class="card profile-card">
                        <div class="foto-container">
                            <img src="../../source/fotopengguna/<?= $dataUsers['FOTO_USERS'] ?>" alt="Profile" class="foto">
                        </div>
                        <h4 style="margin-bottom: 5px;"><?= $dataUsers['USERNAME'] ?></h4>
                        <span class="role-badge"><?= $dataUsers['ROLE'] ?></span>
                    </div>

                    <span class="category-title">Kredensial</span>
                    <div class="card">
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <label>Username <span>(BEFORE: <?= $dataUsers['USERNAME'] ?>)</span></label>
                            <input type="text" name="usn" value="<?= $dataUsers['USERNAME'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Password <span>(BEFORE: <?= $dataUsers['PASS'] ?>)</span></label>
                            <input type="text" name="pass">
                        </div>
                    </div>
                
                    <span class="category-title">Informasi Pribadi</span>
                    <div class="card">
                        <div class="form-group">
                            <label>Nama Lengkap <span>(BEFORE: <?= $dataUsers['NAMA_LENGKAP'] ?>)</span></label>
                            <input type="text" name="nama_lengkap" value="<?= $dataUsers['NAMA_LENGKAP'] ?>">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label>Nomor Telepon <span>(BEFORE: <?= $dataUsers['NO_TLP'] ?>)</span></label>
                                <input type="text" name="no_tlp" value="<?= $dataUsers['NO_TLP'] ?>">
                            </div>
                            <div class="form-group">
                                <label>Email <span>(BEFORE: <?= $dataUsers['EMAIL'] ?>)</span></label>
                                <input type="email" name="email" value="<?= $dataUsers['EMAIL'] ?>">
                            </div>
                        </div>
                    </div>

                    <span class="category-title">Status Akun</span>
                    <div class="card">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                
                                <option value="1" <?= $dataUsers['STATUS']=='1'?'selected':'' ?>>1 atau aktif</option>
                                <option value="0" <?= $dataUsers['STATUS']=='0'?'selected':'' ?>>0 atau nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa fa-save"></i> SUBMIT PERUBAHAN
                    </button>
                    <div id="notif"></div>
                </div>
            </div>
        </form>
    </div>

    <script src="./../shared/js/script.js"></script>
</body>

</html>