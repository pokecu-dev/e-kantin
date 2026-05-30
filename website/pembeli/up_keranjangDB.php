<?php
    require_once __DIR__ . "/../include/koneksi.php";
    require_once __DIR__ . "/../include/session/pembeliC.php";
    // session_start();
    
    header("content-type: application/json");
    
    if (!isset($_SESSION['id_user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Sesi habis, silakan login kembali!']);
        exit();
    }
    
    $id_keranjang = (int)($_POST['id_keranjang'] ?? 0);
    $qty          = (int)($_POST['qty'] ?? 0);
    $id_user      = (int)$_SESSION['id_user'];
    
    if ($id_keranjang <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID keranjang tidak valid!']);
        exit();
    }
    
    // Jika qty = 0, hapus item
    if ($qty === 0) {
        $delete = mysqli_query($conn, 
            "DELETE FROM keranjang 
             WHERE id_keranjang = $id_keranjang 
             AND id_user = $id_user"
        );
        
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Item dihapus dari keranjang!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus item!']);
        }
        exit();
    }
    
    // Update qty
    $update = mysqli_query($conn, 
        "UPDATE keranjang 
         SET qty = $qty 
         WHERE id_keranjang = $id_keranjang 
         AND id_user = $id_user"
    );
    
    if ($update) {
        echo json_encode(['status' => 'success', 'message' => 'Jumlah berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database!']);
    }
    exit();