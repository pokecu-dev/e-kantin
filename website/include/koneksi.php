<?php
    
    $host = "my-database"; 
    $user = "root";
    $pass = "inikantin";
    $db   = "kantin";
    $port = 3306;
    $conn = new mysqli($host, $user, $pass, $db,$port);
    
    
    if ($conn->connect_error) {
        die("Koneksi Gagal: " . mysqli_connect_error());
    }
    // echo 'koneksi berhasil';
?>
