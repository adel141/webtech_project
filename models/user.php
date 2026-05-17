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


    public function deactivateUser($user_id) {
        $sql = "UPDATE users SET is_active = 0 WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function activateUser($user_id) {
        $sql = "UPDATE users SET is_active = 1 WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function getUserByRole($role) {
        $sql = "SELECT * FROM users WHERE role = '$role'";
        return $this->conn->query($sql);
    }

    public function getPendingEmployers($role) {
        $sql = "SELECT * FROM users WHERE role = '$role' AND is_verified = 0 AND is_active = 1 ORDER BY created_at DESC";
        return $this->conn->query($sql);
    }

    public function approveUser($user_id) {
        $sql = "UPDATE users SET is_verified = 1 WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }
}
?>