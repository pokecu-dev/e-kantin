<?php

    require_once __DIR__ . "/Databases.php";
    
    // ambil data saat login :p

    class user extends Databases{

        public function get_data($username,$password){
            // $u = $this->db->real_escape_string(strtolower($username));
            
            $u = $this->sanitizeSTR(strtolower($username));
            $sql = "SELECT * FROM users WHERE USERNAME = '$u'";
            $query = $this->db->query($sql);

            if($query->num_rows > 0){
                $data = $query->fetch_assoc();
                // if ($password == $data["PASS"]) {
                //     return $data;
                // }
                if (password_verify($password,$data["PASS"])) {
                    return $data;
                }
            }
            return false;

        } 

    }

?>