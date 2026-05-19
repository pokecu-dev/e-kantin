<?php

    require_once __DIR__ . "/../../include/koneksi.php";
    require_once __DIR__ . "/../../include/classes/adduserClasses/users.php";
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        // deklarasi
        
        $usn = $_POST['usn'];
        $pass = $_POST['pass'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $no_tlp = $_POST['no_tlp'];
        $email = $_POST['email'];
        
        
        header('content-type: application/json');

        try{

            $usn = strtolower($usn);

            // new obj dari users.php
            $adminobj = new Users($conn);
            // add admin

            $hasil = $adminobj->AddUsers(
                $usn,
                $pass,
                $nama_lengkap,
                $no_tlp,
                $email,
                "PENJUAL",
                '1'
            );
            if($hasil){
                echo json_encode([
                    'status'=>'success',
                    'message'=>'berhasil menambahkan admin!'
                ]);
            }

            else {
                echo json_encode(['status' => 'error','message' => 'ada kesalahan!']);
            }

        }
        catch(Exception $e){
            echo json_encode(['status' => 'error','message' => 'gagal' . $e->getMessage()]);

        }
    }

    

?>
