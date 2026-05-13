<?php

    require_once __DIR__ . "/../classes/upfile/upfile.php";
    require_once __DIR__ . "/../koneksi.php";


    // header('content-type: application/json');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (isset($_FILES['upfile'])) {
            $file = $_FILES['upfile'];

            $id = 1;// testing:D

            $objUp = new upfile($conn);
            $output = $objUp->upload($file,UploadTarget::PROFILE,$id);
            echo $output;
            
        }
        else {
            echo json_encode(['status' => 'error', 'message' => 'Mana filenya, Bwang?']);
        }
    }


