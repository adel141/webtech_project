<?php 
class Outreach {

    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
   function countOutreach($recruiterId ) {
        $sql = "SELECT COUNT(*) FROM recruiter_outreach WHERE recruiter_id = '$recruiterId'";
        $result = $this->conn->query($sql);
        return $result;
    }



}