<?php
    require_once __DIR__ . "/include/koneksi.php";
    require_once __DIR__ . "/include/classes/adduserClasses/users.php";
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


            if($hasil == 1){
                echo json_encode([
                    'status'=>'success',
                    'message'=>'pendaftaran telah berhasil,silahkan kembali ke halaman login!' . $hasil
                ]);
            }
            else if ($hasil == "usn dupe") {
                echo json_encode([
                    'error'=>'error',
                    'message' => 'username sudah terpakai!' . $hasil 
                ]);
            }
            else if ($hasil == "no tlp dupe") {
                echo json_encode([
                    'error'=>'error',
                    'message' => 'nomor telepon sudah terpakai!' . $hasil
                ]);
            }else if ($hasil == "email dupe") {
                echo json_encode([
                    'error'=>'error',
                    'message' => 'email sudah terpakai!' . $hasil
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
