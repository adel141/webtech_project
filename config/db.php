<?php

class Database {

    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "job_portal";

    public $conn;

    public function connect(){

        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        if($this->conn->connect_error){
            die("Connection Failed");
        }

        return $this->conn;
    }
}
?>