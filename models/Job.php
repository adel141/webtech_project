<?php 
class Job{

    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
    function countActiveJobs() {
        $sql = "SELECT COUNT(*) FROM jobs WHERE status = 'active'";
        $result = $this->conn->query($sql);
        return $result;
    }



}