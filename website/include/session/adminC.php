<?php

    require_once __DIR__ . "/loginCheck.php";
    // session_start();

    if (!$_SESSION['role'] == 'ADMIN') {
        header('location: ./../../index.php');
    }