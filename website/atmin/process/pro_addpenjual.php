<?php

    require_once __DIR__ . "/../../include/koneksi.php";
    require_once __DIR__ . "/../../include/classes/adduserClasses/penjual.php";
    // deklarasi
    $usn = $_POST['usn'];
    $pass = $_POST['pass'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_tlp = $_POST['no_tlp'];
    $email = $_POST['email'];

    

    $usn = strtolower($usn);
        
    // new obj dari murid.php
    $adminobj = new penjual($conn);
    // add admin
    $hasil = $adminobj->add(
        $usn,
        $pass,
        $nama_lengkap,
        $no_tlp,
        $email
    );

    if ($hasil) {
        echo "berhasil tambah penjual YEYYYYYYYYY";
    }
    else{
        echo "cieee gagall <br> nih info kenapa bisa gagal: " . $conn->error;
    }

    

?>
