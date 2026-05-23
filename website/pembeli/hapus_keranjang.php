<?php
session_start();
require_once '../include/koneksi.php';


if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
} else {
    $id = 0;
}

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

// echo $id;
if ($id > 0) {
    if ($id_user) {
        
    //    echo "hai";
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang = '$id' AND id_user = '$id_user'");
    } else {
        
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang = '$id'");
    }
}

header("Location: keranjang.php");
exit();
?>