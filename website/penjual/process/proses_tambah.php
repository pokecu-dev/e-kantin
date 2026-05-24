<?php
session_start();
require_once '../../include/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['id_user'];

    // Ambil ID Kantin yang benar
    $q = mysqli_query($conn, "SELECT id FROM list_kantin WHERE id_penjual = '$id_user'");
    $d = mysqli_fetch_assoc($q);
    $id_kantin = $d['id'];

    $nama_menu = $_POST['nama_menu'];
    $desk      = $_POST['desk'];
    $harga     = (int)$_POST['harga'];
    $stok      = (int)$_POST['stok'];
    $kategori  = $_POST['kategori'];
    $status    = $_POST['status'];

    $foto = $_FILES['foto_menu']['name'];
    $tmp  = $_FILES['foto_menu']['tmp_name'];
    $path = "../../source/gambar_menu/" . $foto;

    if (move_uploaded_file($tmp, $path)) {
        // Gunakan Prepared Statement agar lebih aman
        $sql = "INSERT INTO tb_menu (id_kantin, NAMA_MENU, DESK, HARGA, KATEGORI, STOK, STATUS, FOTO_MENU) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        // Pastikan urutan dan tipe datanya benar (i=int, s=string)
        mysqli_stmt_bind_param($stmt, "issisiss", $id_kantin, $nama_menu, $desk, $harga, $kategori, $stok, $status, $foto);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Berhasil!'); window.location='../edit1.php';</script>";
        } else {
            // Jika error, tampilkan errornya
            echo "Error Database: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "Gagal upload gambar!";
    }
}
?>