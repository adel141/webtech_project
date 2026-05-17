<?php
class Catagories{
    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
    public function getAllCatagories() {
        $sql = "SELECT * FROM catagories";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function getCatagoryById($id) {
        $sql = "SELECT * FROM catagories WHERE id = '$id'";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function addCatagory($name) {
        $sql = "INSERT INTO catagories (name) VALUES ('$name')";
        return $this->conn->query($sql);
    }

    public function deleteCatagory($id) {
        $sql = "DELETE FROM catagories WHERE id = '$id'";
        return $this->conn->query($sql);
    }

    public function updateCatagory($id, $name) {
        $sql = "UPDATE catagories SET name = '$name' WHERE id = '$id'";
        return $this->conn->query($sql);
    }

    public function  hasActiveJobs($catagory_id) {
        $sql = "SELECT COUNT(*) AS cnt FROM jobs WHERE catagory_id = '$catagory_id' AND status = 'active'";
        return  $this->conn->query($sql);
        
    }

    public function jobCountByCatagory() {
        $sql = "SELECT c.name AS catagory_name, COUNT(j.id) AS job_count 
                FROM catagories c 
                LEFT JOIN jobs j ON c.id = j.catagory_id AND j.status = 'active' 
                GROUP BY c.id";
        return $this->conn->query($sql);
    }



}j