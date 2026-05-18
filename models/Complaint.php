<?php
require_once '../../config/db.php';

class Complaint{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllComplaints(){
        $sql = "SELECT complaints.*, complaints.submitter_id AS user_id,
                CONCAT('Complaint #', complaints.id) AS subject,
                complaints.description AS message,
                users.name AS user_name, users.email AS user_email
                FROM complaints
                LEFT JOIN users ON complaints.submitter_id = users.id
                ORDER BY complaints.id DESC";
        return $this->conn->query($sql);
    }

    public function getOpenComplaints(){
        $sql = "SELECT * FROM complaints WHERE status = 'open' ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function resolveComplaint($id, $admin_note){
        $id = $this->conn->real_escape_string($id);
        $admin_note = $this->conn->real_escape_string($admin_note);
        $sql = "UPDATE complaints SET status = 'resolved', admin_note = '$admin_note'
                WHERE id = '$id'";
        return $this->conn->query($sql);
    }

    public function countOpenComplaints(){
        $sql = "SELECT COUNT(*) AS cnt FROM complaints WHERE status = 'open'";
        return $this->conn->query($sql);
    }
}
?>
