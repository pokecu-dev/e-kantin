<?php 
    session_start();

    if(!$_SESSION['status'] == 'success' || !isset($_SESSION['status']) ){

        header('location: ./../../index.php');
    }
