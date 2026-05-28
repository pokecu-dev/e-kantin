<?php

    require_once __DIR__ . "/databases.php";
    
    // ambil data saat login :p

    class user extends Databases{

        public function get_data($input,$password){
            // $u = $this->db->real_escape_string(strtolower($username));
            
            $i = $this->sanitizeSTR($input);
            $sql = "SELECT * FROM users WHERE USERNAME = ? OR EMAIL = ? ";
            // $query = $this->db->query($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ss",$i,$i);
            $stmt->execute();
            $result = $stmt->get_result();


            if($result->num_rows > 0){
                $data = $result->fetch_assoc();
                
                if($data['STATUS'] == 0 || $data['STATUS'] == '0'){
                    $stmt->close();
                    return false;
                }

                if (password_verify($password,$data["PASS"])) {
                    $stmt->close();
                    return $data;
                }
            }
            $stmt->close();
            return false;

        } 

    }

?>