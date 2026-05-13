<?php

    require_once __DIR__ . "/../../include/koneksi.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        try{

            header('content-type: application/json');

            $id = $_POST['id'];
            $username = $_POST['usn'];
            $pass = $_POST['pass'];
            $nama_lengkap = $_POST['nama_lengkap'];
            $no_tlp = $_POST['no_tlp'];
            $email = $_POST['email'];

            $pass = password_hash($pass,PASSWORD_DEFAULT);

            $sql = "UPDATE users SET USERNAME='$username', PASS='$pass', NAMA_LENGKAP='$nama_lengkap', NO_TLP='$no_tlp', EMAIL='$email' WHERE ID='$id'";

            $query = $conn->query($sql);

            echo json_encode([
                'status' => 'success',
                'message' => 'berhasil!,mohon refresh halaman!'
            ]);
        }
        catch(Exception $e){
            echo json_encode(['status' => 'error','message' => 'gagal' . $e->getMessage()]);

        }
        

    }
    
?>