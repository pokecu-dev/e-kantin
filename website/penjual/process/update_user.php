<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    header("location: ../../login.php");
    exit();
}

require_once __DIR__ . "/../../include/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user      = $_SESSION['id_user'];
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $no_tlp       = mysqli_real_escape_string($conn, $_POST['no_tlp']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);

    $query_lama = mysqli_query($conn, "SELECT FOTO_USERS FROM users WHERE ID = '$id_user'");
    $data_lama  = mysqli_fetch_assoc($query_lama);
    $foto_sekarang = $data_lama['FOTO_USERS'] ?? '';

    // PROSES UPLOAD MANUAL BYPASS CLASS
    if (isset($_FILES['foto_user']) && $_FILES['foto_user']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['foto_user']['tmp_name'];
        $file_name = $_FILES['foto_user']['name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $foto_baru = "user_" . $id_user . "_" . time() . "." . $ext;
            $target_dir = "../../source/fotopengguna/" . $foto_baru;

            if (move_uploaded_file($file_tmp, $target_dir)) {
                if (!empty($foto_sekarang) && $foto_sekarang !== 'default.jpg' && file_exists("../../source/fotopengguna/" . $foto_sekarang)) {
                    unlink("../../source/fotopengguna/" . $foto_sekarang);
                }
                $foto_sekarang = $foto_baru;
            }
        }
    }

    $query_update = "UPDATE users SET NAMA_LENGKAP = '$nama_lengkap', NO_TLP = '$no_tlp', EMAIL = '$email', FOTO_USERS = '$foto_sekarang' WHERE ID = '$id_user'";

    if (mysqli_query($conn, $query_update)) {
        header("Location: ../profil.php?status=success&msg=" . urlencode("Data profil berhasil diperbarui!"));
        exit();
    } else {
        header("Location: ../profil.php?status=error&msg=" . urlencode("Gagal ke database: " . mysqli_error($conn)));
        exit();
    }
}