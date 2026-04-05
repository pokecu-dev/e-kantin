<?php
    
    // require_once "__DIR__ ./adduser.php";
    // require_once "./adduser.php";
    require_once "./users.php";
    // require_once "/workspaces/e-kantin/website/include/classes/adduserClasses/users.php";

    class Murid extends Users{

        public function add($usn, $pass, $nama_lengkap, $no_tlp, $email,$nisn,$id_kelas,$tempat_lahir,$tanggal_lahir,$alamat_rumah)
        {
            $hasil = $this->AddUsers($usn, $pass, $nama_lengkap, $no_tlp, $email, "MURID");


            if ($hasil) {
                
                $userid = $this->db->insert_id;


                $sql = "INSERT INTO murid (ID_USER,NISN,ID_KELAS,TEMPAT_LAHIR,TANGGAL_LAHIR,ALAMAT_RUMAH)
                        VALUES ('$userid','$nisn','$id_kelas','$tempat_lahir','$tanggal_lahir','$alamat_rumah')";
                return $this->db->query($sql);
            }
            return false;
        } 
        

        
    }
?>