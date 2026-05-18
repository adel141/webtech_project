<?php 
class recentOutreach {

    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
   function getRecentOutreach($recruiterId ) {
        $sql = "SELECT COUNT(*) FROM recruiter_outreach WHERE recruiter_id = '$recruiterId'";
        $result = $this->conn->query($sql);
        return $result;
    }



}