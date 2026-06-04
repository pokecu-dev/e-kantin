<?php
    session_start();

    if(!isset($_SESSION['status']) || $_SESSION['status'] != 'success'){
        header("location: login.php");
        exit();
    }

    if($_SESSION['role'] == 'PEMBELI'){
        header('location: pembeli/pembeli.php');
    }
    elseif ($_SESSION['role'] == 'PENJUAL') {
        header('location: penjual/penjual.php');
    }
    else{
        header('location: atmin/admin.php');
    }

?>