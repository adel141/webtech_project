<?php 
class Responded{

    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
    function countResponded() {
        $sql = "SELECT COUNT(*) FROM jobs WHERE status = 'responded'";
        $result = $this->conn->query($sql);
        return $result;
    }



}