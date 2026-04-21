<?php
    
    $host = getenv("DB_HOST"); 
    $user = getenv("MYSQL_USER");
    $pass = getenv("MYSQL_PASSWORD");
    $db   = getenv("MYSQL_DATABASE");
    $port = getenv("DB_PORT");
    $conn = new mysqli($host, $user, $pass, $db,$port);
    
    
    if ($conn->connect_error) {
        die("Koneksi Gagal: " . mysqli_connect_error());
    }
    // echo 'koneksi berhasil';
?>
