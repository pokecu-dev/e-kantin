<?php
session_start();
// Naik 2 tingkat untuk menemukan folder include/koneksi.php
require_once '../../include/koneksi.php';

// 1. Proteksi Keamanan: Pastikan user sudah login dan ID Menu-nya ada
if (!isset($_SESSION['id_user']) || !isset($_GET['id'])) {
    header("Location: ../edit1.php");
    exit();
}

$id_menu = $_GET['id'];
$id_user = $_SESSION['id_user']; // ID penjual yang sedang login

// 2. Query Soft Delete (Mengubah STATUS menjadi 'nonaktif')
// Kita pakai JOIN ke list_kantin agar penjual TIDAK BISA menghapus produk milik kantin lain lewat URL
$sql = "UPDATE tb_menu m 
        JOIN list_kantin k ON m.id_kantin = k.id 
        SET m.STATUS = 'nonaktif' 
        WHERE m.ID_MENU = ? AND k.id_penjual = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id_menu, $id_user);

// 3. Eksekusi dan Beri Notifikasi
if (mysqli_stmt_execute($stmt)) {
    // Jika berhasil, muncul alert lalu reload halaman utama produk
    echo "<script>
            alert('Produk berhasil dihapus (dinonaktifkan).'); 
            window.location='../edit1.php';
          </script>";
} else {
    // Jika gagal karena error database
    echo "<script>
            alert('Gagal menghapus produk: " . mysqli_error($conn) . "'); 
            window.location='../edit1.php';
          </script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>