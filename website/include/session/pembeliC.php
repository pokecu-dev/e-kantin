<?php
    require_once __DIR__ . "/loginCheck.php";
    // session_start();

    if ($_SESSION['role'] != 'PEMBELI') {
        header('location: ./../../index.php');
        exit();
    }