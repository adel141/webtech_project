<?php 
class Client{

    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
   function countClients($recruiterId ) {
        $sql = "SELECT COUNT(*) FROM recruiter_clients WHERE recruiter_id = '$recruiterId'";
        $result = $this->conn->query($sql);
        return $result;
    }



}