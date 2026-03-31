<?php
    class user{

        private $db;

        public function __construct($database_connection)
        {
            $this->db = $database_connection;
        }

        public function get_data($username,$password){
            $u = $this->db->real_escape_string(strtolower($username));
            // $u = $this->db->real_escape_string($username);
            
            $sql = "SELECT * FROM user WHERE USERNAME = '$u'";
            $query = $this->db->query($sql);

            if($query->num_rows > 0){
                $data = $query->fetch_assoc();
                if ($password == $data["PASS"]) {
                    return $data;
                }
            }
            return false;

        } 

    }

?>