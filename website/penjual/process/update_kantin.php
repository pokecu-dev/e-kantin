<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'success') {
    header("location: ../../login.php");
    exit();
}

// 2. Hubungkan ke database & Class Utama upfile
require_once __DIR__ . "/../../include/koneksi.php";
require_once __DIR__ . "/../../include/classes/upfile/upfile.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user     = $_SESSION['id_user']; // Nilainya 15
    $nama_kantin = mysqli_real_escape_string($conn, $_POST['nama_kantin']);
    $id_kantin   = isset($_POST['id_kantin']) ? mysqli_real_escape_string($conn, $_POST['id_kantin']) : '';

    // Ambil data kantin lama dari database
    $query_lama = mysqli_query($conn, "SELECT ID, FOTO_KANTIN FROM list_kantin WHERE id_penjual = '$id_user'");
    $data_lama  = mysqli_fetch_assoc($query_lama);

    if (!$data_lama) {
        header("Location: ../profil.php?status=error&msg=" . urlencode("Data kantin tidak ditemukan!"));
        exit();
    }

    if (empty($id_kantin)) {
        $id_kantin = $data_lama['ID']; // Nilainya 2
    }

    $foto_sekarang = $data_lama['FOTO_KANTIN'] ?? '';

    // 3. PROSES UP FILE MENGGUNAKAN CLASS UTAMA KAMU
    if (isset($_FILES['foto_kantin']) && $_FILES['foto_kantin']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto_kantin'];

        // Panggil class upfile 
        $objUp = new upfile($conn);
        
        // Jalankan fungsi upload bawaan sistemmu
        // Kita gunakan ob_start agar jika fungsi ini melakukan "echo" di dalam, tidak merusak redirect kita
        ob_start();
        $raw_output = $objUp->upload($file, UploadTarget::PROFILE, $id_kantin);
        $buffered_output = ob_get_clean();

        // Gabungkan output jika ada yang di-echo langsung oleh fungsi upload-mu
        $hasil_upload = trim($raw_output . $buffered_output);

        // --- DETEKSI FORMAT HASIL UPLOAD ---
        $nama_file_fix = '';

        // JIKA OUTPUTNYA FORMAT JSON (Contoh: {"status":"success", "fileName":"kantin_2.jpg"})
        if (str_starts_with($hasil_upload, '{') && str_ends_with($hasil_upload, '}')) {
            $json_data = json_decode($hasil_upload, true);
            if (isset($json_data['fileName'])) {
                $nama_file_fix = $json_data['fileName'];
            } elseif (isset($json_data['message']) && !isset($json_data['status'])) {
                $nama_file_fix = $json_data['message']; // Kadang nama file ditaruh di message
            }
        } else {
            // JIKA OUTPUTNYA LANGSUNG TEKS NAMA FILE (Contoh: kantin_2_173921.jpg)
            $nama_file_fix = $hasil_upload;
        }

        // Jika berhasil mendapatkan nama file baru yang valid
        if (!empty($nama_file_fix) && !str_contains($nama_file_fix, 'error') && !str_contains($nama_file_fix, 'Mana filenya')) {
            // Hapus foto lama di folder fotokantin jika ada
            if (!empty($foto_sekarang) && file_exists("../../source/foto_kantin/" . $foto_sekarang)) {
                unlink("../../source/foto_kantin/" . $foto_sekarang);
            }
            $foto_sekarang = $nama_file_fix;
        }
    }

    // 4. Eksekusi Update ke database (Nama Kantin & Foto Baru)
    $query_update = "UPDATE list_kantin SET 
                        NAMA_KANTIN = '$nama_kantin', 
                        FOTO_KANTIN = '$foto_sekarang' 
                     WHERE ID = '$id_kantin' AND id_penjual = '$id_user'";

    if (mysqli_query($conn, $query_update)) {
        header("Location: ../profil.php?status=success&msg=" . urlencode("Profil kantin berhasil diperbarui!"));
        exit();
    } else {
        header("Location: ../profil.php?status=error&msg=" . urlencode("Gagal query database: " . mysqli_error($conn)));
        exit();
    }
} else {
    header("location: ../profil.php");
    exit();
}
?>