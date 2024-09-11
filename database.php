<?php
        
    class Database{
            
            private $host = "localhost";    
            private $username = "root";
            private $password = "";
            private $dbname = "book";

           
            protected $conn;
            
            
            function connect(){
                if($this->conn === null){
                        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
                }

                return $this->conn;
            }

    }

   // $obj = new Database();
    //$obj->connect();
