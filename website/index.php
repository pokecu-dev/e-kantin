<?php
    session_start();

    if(!isset($_SESSION['status']) || $_SESSION['status'] != 'success'){
        header("location: login.php");
        exit();
    }

    if($_SESSION['role'] == 'GURU' || $_SESSION['role'] == 'MURID'){
        header('location: pembeli/pembeli.php');
    }
    elseif ($_SESSION['role'] == 'PENJUAL') {
        header('location: penjual/penjual.php');
    }
    else{
        // admin ini nanti
    }

?>