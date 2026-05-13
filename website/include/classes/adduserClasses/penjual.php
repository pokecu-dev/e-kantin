<?php
    
   
    require_once __DIR__ . '/users.php';  
    
    class penjual extends Users{

        public function add($usn, $pass, $nama_lengkap, $no_tlp, $email)
        {
            $hasil = $this->AddUsers($usn, $pass, $nama_lengkap, $no_tlp, $email, "PENJUAL");


            if ($hasil) {
                
                // $userid = $this->db->insert_id;


                // $sql = "INSERT INTO murid (ID_USER)
                //         VALUES ('$userid')";
                // return $this->db->query($sql);
                return true;
            }
            return false;
        } 
        

        
    }
?>