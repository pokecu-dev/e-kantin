<?php

require_once __DIR__ . "/../include/session/pembeliC.php";
require_once __DIR__ . "/../include/koneksi.php";

// 2. Cek apakah tombol submit dari modal sudah diklik
if (isset($_POST['update_profile'])) {
    $id_user      = $_SESSION['id_user'];
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $no_tlp       = mysqli_real_escape_string($conn, $_POST['no_tlp']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);

    // Ambil data user lama untuk tahu nama foto lamanya
    $query_lama = mysqli_query($conn, "SELECT FOTO_USERS FROM users WHERE ID = '$id_user'");
    $data_lama  = mysqli_fetch_array($query_lama);
    $foto_lama  = $data_lama['FOTO_USERS'];

    // Inisialisasi nama file foto yang akan disimpan di DB
    $nama_foto_baru = $foto_lama; 

    // 3. Proses Logika Upload Foto (Jika ada file baru yang diunggah)
    if (isset($_FILES['foto_user']) && $_FILES['foto_user']['error'] === 0) {
        $file_tmp  = $_FILES['foto_user']['tmp_name'];
        $file_name = $_FILES['foto_user']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Ekstensi file yang diperbolehkan
        $ekstensi_boleh = array('jpg', 'jpeg', 'png');

        if (in_array($file_ext, $ekstensi_boleh)) {
            // Berikan nama unik untuk foto baru agar tidak bentrok (misal: user_1_17169421.png)
            $nama_foto_baru = "user_" . $id_user . "_" . time() . "." . $file_ext;
            $folder_tujuan  = "../source/fotopengguna/" . $nama_foto_baru;

            // Pindahkan file dari folder temporary ke folder tujuan project
            if (move_uploaded_file($file_tmp, $folder_tujuan)) {
                // Hapus foto lama dari server jika bukan foto default
                if (!empty($foto_lama) && $foto_lama != 'default.jpg' && file_exists("../source/fotopengguna/" . $foto_lama)) {
                    unlink("../source/fotopengguna/" . $foto_lama);
                }
            } else {
                echo "<script>alert('Gagal mengunggah foto ke folder server.'); window.location='profil.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Format foto harus JPG, JPEG, atau PNG!'); window.location='profil.php';</script>";
            exit();
        }
    }

    // 4. Update data teks beserta nama foto ke database
    $query_update = "UPDATE users SET 
                        NAMA_LENGKAP = '$nama_lengkap', 
                        NO_TLP = '$no_tlp', 
                        EMAIL = '$email', 
                        FOTO_USERS = '$nama_foto_baru' 
                     WHERE ID = '$id_user'";

    if (mysqli_query($conn, $query_update)) {
        // Jika sukses, lempar kembali ke halaman profil dengan pesan sukses
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
    } else {
        // Jika gagal query database
        echo "<script>alert('Gagal memperbarui database: " . mysqli_error($conn) . "'); window.location='profil.php';</script>";
    }
} else {
    // Jika file ini diakses langsung tanpa lewat form modal
    header("location: profil.php");
    exit();
}
?>