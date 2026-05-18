<?php

class Database{

    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "job_portal";

    public function connect(){
        $conn = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->database
        );

        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}
?>
