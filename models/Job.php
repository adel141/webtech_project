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

    function countActiveJobsByCategory() {
        $sql = "SELECT category_id, COUNT(*) AS cnt FROM jobs WHERE status = 'active' GROUP BY category_id";
        $result = $this->conn->query($sql);
        return $result;
    }




}