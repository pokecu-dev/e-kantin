<?php
    require_once __DIR__ . "/../../include/koneksi.php";
    require_once __DIR__ . "/../../include/classes/adduserClasses/users.php";
    // deklarasi

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $usn = $_POST['usn'];
        $pass = $_POST['pass'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $no_tlp = $_POST['no_tlp'];
        $email = $_POST['email'];

        header('content-type: application/json');

        try{

            $usn = strtolower($usn);

            // new obj dari users.php
            $muridobj = new Users($conn);
            // add murid
            $hasil = $muridobj->AddUsers(
                $usn,
                $pass,
                $nama_lengkap,
                $no_tlp,
                $email,
                "PEMBELI",
                '1'
                );

            // if($hasil){
            //     echo json_encode([
            //         'status'=>'success',
            //         'message'=>'berhasil menambahkan PEMBELI!'
            //     ]);
            // }
            if($hasil == 1){
                echo json_encode([
                    'status'=>'success',
                    'message'=>'pembeli telah berhasil ditambahkan!' 
                ]);
            }
            else if ($hasil == "usn dupe") {
                echo json_encode([
                    'error'=>'error',
                    'message' => 'username sudah terpakai!'  
                ]);
            }
            else if ($hasil == "no tlp dupe") {
                echo json_encode([
                    'error'=>'error',
                    'message' => 'nomor telepon sudah terpakai!' 
                ]);
            }else if ($hasil == "email dupe") {
                echo json_encode([
                    'error'=>'error',
                    'message' => 'email sudah terpakai!' 
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
