<?php
    require_once __DIR__ . "/../include/koneksi.php";
    session_start();

    if (!isset($_SESSION['id_user'])) {
        header("content-type: application/json");
        echo json_encode(['status' => 'error', 'message' => 'Sesi habis, silakan login kembali!']);
        exit();
    }

    $id_menu = (int)($_POST['id_menu'] ?? 0);
    $qty     = (int)($_POST['qty'] ?? 1);
    $id_user = (int)$_SESSION['id_user'];

    header("content-type: application/json");

    if ($id_menu > 0 && $qty > 0) {

        $update = mysqli_query($conn, 
            "UPDATE keranjang 
             SET qty = '$qty' 
             WHERE id_menu = '$id_menu' 
             AND id_user = '$id_user'"
        );

        if ($update) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Jumlah berhasil diperbarui!'
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal memperbarui database!'
            ]);
            exit();
        }

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data menu atau jumlah tidak valid!'
        ]);
        exit();
    }