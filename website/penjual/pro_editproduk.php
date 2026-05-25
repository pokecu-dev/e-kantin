<?php 
    require_once __DIR__ . "/../include/proses(universal)/upfile.php";
    require_once __DIR__ . "/../include/koneksi.php";
    require_once __DIR__ . "/../include/session/penjualC.php";


    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $id_menu = $_POST['id'];
        $menu = $_POST['nama_menu'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $kategori = $_POST['kategori'];
        $status = $_POST['status'];
        $desk = $_POST['desk'];

        header("content-type: application/json");

        try{


            ob_clean();

            $sql = "UPDATE tb_menu SET NAMA_MENU = ? , HARGA = ? , KATEGORI = ? , STOK = ? , STATUS = ? , DESK = ? WHERE ID_MENU = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssissi",$menu,$harga,$kategori,$stok,$status,$desk,$id_menu);
            
            if($stmt->execute()){
                echo json_encode([
                    'status' => 'success',
                    'message' => 'data berhasil di perbarui'
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
        catch(Exception $e){
            ob_clean();
            echo json_encode([
                'status' => 'error',
                'message' => 'error:'. $e->getMessage()
            ]);
        }

    }



?>