<?php
session_start();
require_once '../include/koneksi.php';


if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    $id = 0;
}

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

if ($id > 0) {
    if ($id_user) {
       
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang = '$id' AND id_user = '$id_user'");
    } else {
        
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang = '$id'");
    }
}

header("Location: keranjang.php");
exit();
?>