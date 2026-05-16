<?php

    require_once __DIR__ . "/../include/koneksi.php";

    session_start();
    $id_menu = (int)($_POST['id_menu'] ?? 0);
    $qty = (int)($_POST['qty'] ?? 1);
    $id_user = (int)$_SESSION['id_user'];
    header("content-type: application/json");
    
    if ($id_menu > 0 && $qty > 0) {
        $cek_keranjang = mysqli_query($conn,
            "SELECT * FROM keranjang 
            WHERE id_menu='$id_menu' 
            AND id_user='$id_user'"
        );
        if (mysqli_num_rows($cek_keranjang) > 0) {
            mysqli_query($conn,
                "UPDATE keranjang 
                SET qty = qty + $qty 
                WHERE id_menu='$id_menu' 
                AND id_user='$id_user'"
            );
        } else {
            mysqli_query($conn,
                "INSERT INTO keranjang(id_user,id_menu,qty)
                VALUES('$id_user','$id_menu','$qty')"
            );
        }
        echo json_encode([
            'status' => 'success',
            'message' => 'berhasil!'
        ]);
        exit();
    }
    else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Data menu atau jumlah tidak valid!'
        ]);
        exit();
    }
