<?php

    require_once __DIR__ . "/../../include/koneksi.php";
    require_once __DIR__ . "/../../include/classes/adduserClasses/admin.php";
    // deklarasi
    $usn = $_POST['usn'];
    $pass = $_POST['pass'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_tlp = $_POST['no_tlp'];
    $email = $_POST['email'];

    

    $usn = strtolower($usn);
        
    // new obj dari murid.php
    $adminobj = new admin($conn);
    // add admin
    $hasil = $adminobj->add(
        $usn,
        $pass,
        $nama_lengkap,
        $no_tlp,
        $email
    );

    if ($hasil) {
        echo "berhasil tambah admin YEYYYYYYYYY";
    }
    else{
        echo "cieee gagall <br> nih info kenapa bisa gagal: " . $conn->error;
    }

    

?>
