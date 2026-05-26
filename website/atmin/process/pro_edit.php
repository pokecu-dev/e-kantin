<?php

    require_once __DIR__ . "/../../include/koneksi.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        try{

            header('content-type: application/json');

            $id = $_POST['id'];
            $username = $_POST['usn'];
            $pass = $_POST['pass'] ?? 0;
            $nama_lengkap = $_POST['nama_lengkap'];
            $no_tlp = $_POST['no_tlp'];
            $email = $_POST['email'];
            $status = $_POST['status'];

            if($pass){
                
                $pass = password_hash($pass,PASSWORD_DEFAULT);
    
                $sql = "UPDATE users SET USERNAME= ? , PASS = ? , NAMA_LENGKAP = ? , NO_TLP = ? , EMAIL = ? , STATUS = ? WHERE ID = ? ";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssi",$username,$pass,$nama_lengkap,$no_tlp,$email,$status,$id);
                if($stmt->execute()){
    
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'berhasil!,mohon tunggu 1 detik untuk auto refresh!(jika dalam waktu 1 detik tidak refresh,mohon untuk refresh manual atau submit lagi)'
                    ]);
                }
                else{
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'gagal memperbarui data' . $stmt->error
                    ]);
                }
                $stmt->close();

            }
            else{
                $sql = "UPDATE users SET USERNAME= ? , NAMA_LENGKAP = ? , NO_TLP = ? , EMAIL = ? , STATUS = ? WHERE ID = ? ";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi",$username,$nama_lengkap,$no_tlp,$email,$status,$id);
                if($stmt->execute()){
    
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'berhasil!,mohon tunggu 1 detik untuk auto refresh!(jika dalam waktu 1 detik tidak refresh,mohon untuk refresh manual atau submit lagi)'
                    ]);
                }
                else{
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'gagal memperbarui data' . $stmt->error
                    ]);
                }
            }

        }
        catch(Exception $e){
            echo json_encode(['status' => 'error','message' => 'gagal' . $e->getMessage()]);

        }
        

    }
    
?>