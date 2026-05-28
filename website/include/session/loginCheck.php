<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // session_start();

    if(!$_SESSION['status'] == 'success' || !isset($_SESSION['status']) ){
        // echo "hia";
        header('location: ./../../index.php');
    }