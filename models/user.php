<?php
require_once '../../config/db.php';
class User{
    
    private $conn;
    public function __construct($db){
        $this->conn = $db;
        
    }
    
    public function countByRole() {
    $sql= 'SELECT role, COUNT(*) AS cnt FROM users WHERE is_active = 1 GROUP BY role';
    return $this->conn->query($sql);
    }

    public function getPendingEmployers($role) {
        $sql = "SELECT * FROM users WHERE role = ? AND is_verified = 0 AND is_active = 1 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        return $stmt->get_result();
    }
}