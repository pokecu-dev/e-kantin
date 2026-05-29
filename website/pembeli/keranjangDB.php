<?php

    require_once __DIR__ . "/../include/koneksi.php";

    session_start();
    $id_menu   = (int)($_POST['id_menu'] ?? 0);
    $qty       = (int)($_POST['qty'] ?? 1);
    $id_user   = (int)$_SESSION['id_user'];
    $action    = $_POST['action'] ?? '';
    $id_kantin = (int)($_POST['id_kantin'] ?? 0); // Ambil id_kantin dari form detail_menu

    header("content-type: application/json");
    
    if ($id_menu > 0 && $qty > 0) {
        
    
        if ($action === 'add_to_cart') {
            $cek_keranjang = $conn->query(
                "SELECT * FROM keranjang 
                WHERE id_menu='$id_menu' 
                AND id_user='$id_user'"
            );
            if ($cek_keranjang->num_rows > 0) {
                $conn->query(
                    "UPDATE keranjang 
                    SET qty = qty + $qty 
                    WHERE id_menu='$id_menu' 
                    AND id_user='$id_user'"
                );
            } else {
                $conn->query(
                    "INSERT INTO keranjang(id_user,id_menu,qty)
                    VALUES('$id_user','$id_menu','$qty')"
                );
            }
            
            echo json_encode([
                'status' => 'success', 
                'redirect' => 'cart',
                'message' => 'Berhasil ditambahkan ke keranjang!'
            ]);
            exit();
        } 
        
        else if ($action === 'buy_now') {
            
            echo json_encode([
                'status'    => 'success', 
                'redirect'  => 'buy_now',
                'id_kantin' => $id_kantin, // dikirim balik ke js
                'id_menu'   => $id_menu,   // dikirim balik ke js
                'qty'       => $qty        // dikirim balik ke js
            ]);
            exit();
        } 
        
        else {
            echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
            exit();
        }
    }
    else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data menu atau jumlah tidak valid!'
        ]);
        exit();
    }