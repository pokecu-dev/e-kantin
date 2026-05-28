<?php

    require_once __DIR__ . "/../include/koneksi.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $idmenu = $_POST['id_menu'];
        $iduser = $_POST['id_user'];
        $idkantin = $_POST['id_kantin'];
        $desk = $conn->real_escape_string($_POST['desk']);
        $rating = $_POST['rating'];

        $sql = "INSERT INTO rating (ID_MENU,ID_USER,ID_KANTIN,DESK,RATING) VALUES (
        '$idmenu','$iduser','$idkantin','$desk','$rating')";
        if($conn->query($sql)){
            
            header("Location: pesanan.php");
            exit();
        }         
        else{
            echo "gagal menambahkan rating: " . $conn->error;
        }

    }

?>
