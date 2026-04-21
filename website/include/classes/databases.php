<?php
    class Databases{
        
        protected $db;

        public function __construct($database_connection){
            $this->db = $database_connection;
        }

        protected function sanitizeSTR($input){ // real escape string
            // ReEsStr = Real Escape String 
            return $this->db->real_escape_string(trim($input));
        }
        
        

    }
?>