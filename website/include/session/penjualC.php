<?php

    require_once __DIR__ . "/loginCheck.php";
    // session_start();

    if ($_SESSION['role'] != 'PENJUAL') {
        header('location: ./../../index.php');
    }