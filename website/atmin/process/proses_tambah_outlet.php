<?php
// =========================
// SESSION & KONEKSI
// =========================
session_start();
require_once __DIR__ . "/../../include/session/adminC.php"; // Proteksi halaman admin
require_once __DIR__ . "/../../include/koneksi.php";

// Pastikan request datang dari method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Ambil dan Amankan Data Input
    $nama_kantin = mysqli_real_escape_string($conn, trim($_POST['nama_kantin']));
    $id_penjual  = intval($_POST['id_user']); // ID dari select user penjual

    // Validasi input tidak boleh kosong
    if (empty($nama_kantin) || empty($id_penjual)) {
        echo "
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap' rel='stylesheet'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        
        <style>
            .swal2-popup {
                font-family: 'Poppins', sans-serif !important;
            }
        </style>
        
        <script>
            window.onload = function() {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Semua data wajib diisi!',
                    icon: 'warning',
                    confirmButtonColor: '#F47B20'
                }).then(() => {
                    window.history.back();
                });
            };
        </script>";
        exit();
    }

    // 2. Set Foto ke Default karena Input Banner sudah Dihapus
    $nama_foto_baru = "default.jpg";

    // 3. Query Insert ke Tabel list_kantin
    $sql_insert = "INSERT INTO list_kantin (id_penjual, nama_kantin, foto_kantin) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql_insert);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iss", $id_penjual, $nama_kantin, $nama_foto_baru);
        
        if (mysqli_stmt_execute($stmt)) {
            // Berhasil menyimpan data, lempar balik ke outlet.php menggunakan SweetAlert2
            echo "
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap' rel='stylesheet'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            
            <style>
                .swal2-popup {
                    font-family: 'Poppins', sans-serif !important;
                }
            </style>
            
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Outlet/Kantin baru berhasil ditambahkan!',
                        icon: 'success',
                        confirmButtonColor: '#F47B20'
                    }).then(() => {
                        window.location.href = '../oulet.php'; 
                    });
                };
            </script>";
        } else {
            // Jika query gagal eksekusi (misal ID penjual duplikat/sudah punya kantin)
            echo "
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap' rel='stylesheet'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            
            <style>
                .swal2-popup {
                    font-family: 'Poppins', sans-serif !important;
                }
            </style>
            
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Gagal menyimpan data. Penjual mungkin sudah memiliki kantin.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    }).then(() => {
                        window.history.back();
                    });
                };
            </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Gagal menyiapkan query database.";
    }

} else {
    // Jika diakses langsung tanpa melalui form POST
    header("Location: ../oulet.php");
    exit();
}

mysqli_close($conn);
?>