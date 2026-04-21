<?php
    
    
    require_once __DIR__ . "/../databases.php";
    
    class tes extends Databases{
        protected $usn;
        protected $pass;
        protected $nama_lengkap;
        protected $no_tlp;
        protected $email;
        protected $role;

        public function AddUsers($usn,$pass,$nama_lengkap,$no_tlp,$email,$role){
            $usn = $this->db->real_escape_string($usn);
            $pass = $this->db->real_escape_string($pass);
            $nama_lengkap = $this->db->real_escape_string($nama_lengkap);
            $no_tlp = $this->db->real_escape_string($no_tlp);
            $email = $this->db->real_escape_string($email);
            $role = $this->db->real_escape_string($role);

            $sql = "INSERT INTO users (USERNAME,PASS,NAMA_LENGKAP,NO_TLP,EMAIL,ROLE) VALUES ('$usn','$pass','$nama_lengkap','$no_tlp','$email','$role')";

            return $this->db->query($sql);

        }
    }


?>
