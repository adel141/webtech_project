<?php
class Application{
    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
    function countRecentApplications() {
        $sql = "SELECT COUNT(*) FROM applications WHERE applied_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $result = $this->conn->query($sql);
        return $result;
    }
}