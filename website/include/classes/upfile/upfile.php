<?php

    require_once __DIR__ . "/../databases.php";

    enum UploadTarget: string {
        case PROFILE = 'photoprofil';
        case KANTIN = 'fotokantin';
        case MENU = 'gambarmenu';
    }

    class upfile extends Databases{
        private $allwEx = ['image/jpeg','image/png','image/jpg'];
        private $maxSize = 5242880; // 5MB
        // private $updir = __DIR__ . "/../../../source/tmp/"; // folder temporary/sementara
        private $updirPP = __DIR__ . "/../../../source/fotopengguna/"; // folder photo profil pengguna
        private $updirFK = __DIR__ . "/../../../source/foto_kantin/"; // folder foto kantin
        private $updirFM = __DIR__ . "/../../../source/gambar_menu/"; // folder gambar menu
        
    
        public function upload ($file,UploadTarget $target,$id) {
            
            // header('Content-Type: application/json');

            try{

                $targetDir = match($target){
                    UploadTarget::PROFILE => $this->updirPP,
                    UploadTarget::KANTIN => $this->updirFK,
                    UploadTarget::MENU => $this->updirFM,

                };

                // pengecekan basic :D
                if(!is_dir($targetDir)){
                    error_log("Folder Error: FOLDER NYA SALAH WOI:<");
                    throw new Exception("Folder tujuan tidak ditemukan Bwang:D");
                }

                if(!is_writable($targetDir)){
                    error_log("Folder Error: folder tujuan bermasalah");
                    throw new Exception("Folder tujuan nggak bisa ditulis Bwang:p,coba jalanin 'chown -R 33:33 ./source' di terminal:D");
                    
                }

                if(!isset($file) || $file['error'] !== UPLOAD_ERR_OK){
                    // error_log("File Error");
                    // throw new Exception("file tidak ditemukan atau rusak!");
                    $errCode = $file['error'] ?? 'No File';
                    error_log("Upload Error [PHP_ERR]: Kode Error PHP -> " . $errCode);
                    
                    // Kasih pesan spesifik berdasarkan kode error PHP
                    $msg = match($errCode) {
                        UPLOAD_ERR_INI_SIZE   => "File kegedean (melebihi upload_max_filesize di php.ini)",
                        UPLOAD_ERR_FORM_SIZE  => "File kegedean dibanding limit di form HTML",
                        UPLOAD_ERR_PARTIAL    => "File cuma terupload setengah, coba lagi bwang",
                        UPLOAD_ERR_NO_FILE    => "Gak ada file yang dikirim!",
                        default               => "File rusak atau sistem lagi sibuk."
                    };
                    throw new Exception($msg);
                }

                if($file['size'] > $this->maxSize){
                    throw new Exception("Ukuran File melebihi 2 mb");
                }

                $FInfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $FInfo->file($file['tmp_name']);
                if(!in_array($mime,$this->allwEx)){
                    error_log("Warning: user mencoba upload tipe file terlarang: $mime");
                    throw new Exception("Format file tidak didukung.");
                }

                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = bin2hex(random_bytes(16)) . "." . $ext;
            
                if (!move_uploaded_file($file['tmp_name'], $targetDir . $newFileName)) {
                    error_log("Gagal memindahkan file ke: " . $targetDir);
                    throw new Exception("Gagal menyimpan file ke server.");
                }
                
                $this->sveToDB($target,$newFileName,$id);

                return json_encode([
                'status' => 'success',
                'message' => 'Foto profil berhasil diperbarui.'
                ]);

            }
            catch(Exception $e){

                return json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
            
            
        }

        public function sveToDB(UploadTarget $target, $filename, $id){
            $safeId = (int)$id;

            $sql = match($target){
                UploadTarget::PROFILE => "UPDATE users SET FOTO_USERS = ? WHERE ID=?",
                UploadTarget::KANTIN => "UPDATE list_kantin SET FOTO_KANTIN = ? WHERE ID=?",
                UploadTarget::MENU => "UPDATE tb_menu SET FOTO_MENU = ? WHERE ID_MENU=?",
            };


            // $query = $this->db->query($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si",$filename,$safeId);
            


            if (!$stmt->execute()) {
                $errorMsg = $stmt->error;
                $stmt->close();
                
                error_log("Database Error: " . $errorMsg);
                throw new Exception("Gagal mengupdate database.");
            }
            $stmt->close();
            

        }
    }

            


?>