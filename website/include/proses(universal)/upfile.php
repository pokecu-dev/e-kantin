<?php

    require_once __DIR__ . "/../classes/upfile/upfile.php";
    require_once __DIR__ . "/../koneksi.php";


    // header('content-type: application/json');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_FILES['upfile'])) {

            $file = $_FILES['upfile']; 
            $id = $_POST['id'];
            $objUp = new upfile($conn);
            
            if(isset($_POST['type']) && $_POST['type'] == 'photo-profile'){
                $output = $objUp->upload($file,UploadTarget::PROFILE,$id);
                echo $output;
            }
            elseif (isset($_POST['type']) && $_POST['type'] == 'photo-kantin') {
                $output = $objUp->upload($file,UploadTarget::KANTIN,$id);
                echo $output;
            }
            elseif (isset($_POST['type']) && $_POST['type'] == 'photo-menu') {
                $output = $objUp->upload($file,UploadTarget::MENU,$id);
                echo $output;
            }
            
        }
        else {
            echo json_encode(['status' => 'error', 'message' => 'Mana filenya, Bwang?']);
        }
    }


