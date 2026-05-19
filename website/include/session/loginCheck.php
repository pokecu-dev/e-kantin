<?php 

    if ($_SESSION === PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['status']) || $_SESSION['status'] != 'success'){
        header('location: ./../../login.php');
    }

?>