
 <?php
    
    
    require_once __DIR__ . "/../databases.php";
    
    class Users extends Databases{
        protected $usn;
        protected $pass;
        protected $nama_lengkap;
        protected $no_tlp;
        protected $email;
        protected $role;

        public function AddUsers($usn,$pass,$nama_lengkap,$no_tlp,$email,$role,$status){

            $usn = $this->sanitizeSTR($usn);
            $pass = $this->sanitizeSTR($pass);
            $nama_lengkap = $this->sanitizeSTR($nama_lengkap);
            $no_tlp = $this->sanitizeSTR($no_tlp);
            $email = $this->sanitizeSTR($email);
            $role = $this->sanitizeSTR($role);
            $status = $this->sanitizeSTR($status);
            
            $pass = password_hash($pass,PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (USERNAME,PASS,NAMA_LENGKAP,NO_TLP,EMAIL,ROLE,STATUS) VALUES ( ? , ? , ? , ? , ? , ? , ?)";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sssssss",$usn,$pass,$nama_lengkap,$no_tlp,$email,$role,$status);

            $stmt->execute();

            return $stmt->close();

            // return $this->db->query($sql);

        }
    }


?>
