<?php

session_start();

require_once __DIR__ . "/../../include/koneksi.php";
require_once __DIR__ . "/../../include/classes/adduserClasses/users.php";

// Debug aktif
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data form
    $id_kantin   = isset($_POST['id_kantin']) ? intval($_POST['id_kantin']) : 0;
    $status_baru = isset($_POST['status']) ? trim($_POST['status']) : '';

    // Validasi
    if ($id_kantin <= 0) {
        die("ID kantin tidak valid");
    }

    if ($status_baru !== '0' && $status_baru !== '1') {
        die("Status tidak valid");
    }

    // Query update
    $query = "UPDATE list_kantin SET STATUS = ? WHERE ID = ?";

    $stmt = mysqli_prepare($conn, $query);

    // Cek prepare
    if (!$stmt) {
        die("Prepare gagal: " . mysqli_error($conn));
    }

    // Bind parameter
    mysqli_stmt_bind_param($stmt, "si", $status_baru, $id_kantin);

    // Execute
    if (mysqli_stmt_execute($stmt)) {

       
        mysqli_stmt_close($stmt);

        
        header("Location: ../editoutlet.php?id=$id_kantin&update=success");
        exit();

    } else {

        die("Gagal update status: " . mysqli_stmt_error($stmt));
    }

} else {

    // Kalau bukan POST
    header("Location: ../editoutlet.php");
    exit();
}