<?php
    // versi adin:v
    require_once __DIR__ . "/../../include/koneksi.php";
    require_once __DIR__ . "/../../include/session/penjualC.php";
    // require_once __DIR__ . "/../../include/proses(universal)/upfile.php";
    require_once __DIR__ . "/../../include/classes/upfile/upfile.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $id = $_POST['id_kantin'];
        $iduser = $_SESSION['id_user'];

        $file = $_FILES['foto_kantin'] ?? '';
        $fileQ = $_FILES['foto_qris'] ?? '';
        $nama_kantin = $_POST['nama_kantin'] ?? '';

        $obj = new upfile($conn);
        $result = false;
        $resultQ = false;

        if(isset($file) && $_FILES['foto_kantin']['error'] === UPLOAD_ERR_OK){
            $result = $obj->upload($file,UploadTarget::KANTIN,$id);
        }
        if(isset($fileQ) && $_FILES['foto_qris']['error'] === UPLOAD_ERR_OK){
            $resultQ = $obj->upload($fileQ,UploadTarget::QRIS,$id);
        }

        $sql = "UPDATE list_kantin SET NAMA_KANTIN = ? WHERE ID = ? AND ID_PENJUAL = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii",$nama_kantin,$id,$iduser);


        if($result || $resultQ || $stmt->execute() ){
            header("Location: ../profil.php?status=success&msg=" . urlencode("Profil kantin berhasil diperbarui!"));
            // echo $result;
            // echo $resultQ;
            $stmt->close();
            exit();
        }
        else{
            header("Location: ../profil.php?status=error&msg=" . urlencode("Gagal query database: " . mysqli_error($conn)));
            $stmt->close();
            exit();
        }


    }
    else{
        header("location: ../profil.php");
        exit();
    }