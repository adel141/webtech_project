<?php
class Category{
    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
    public function getAllCategories() {
        $sql = "SELECT * FROM categories";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = '$id'";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function addCategory($name) {
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        return $this->conn->query($sql);
    }

    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE id = '$id'";
        return $this->conn->query($sql);
    }

    public function updateCategory($id, $name) {
        $sql = "UPDATE categories SET name = '$name' WHERE id = '$id'";
        return $this->conn->query($sql);
    }

    public function  hasActiveJobs($category_id) {
        $sql = "SELECT COUNT(*) AS cnt FROM jobs WHERE category_id = '$category_id' AND status = 'active'";
        return  $this->conn->query($sql);
        
    }


    public function countCategory(){
        $sql = "SELECT COUNT(*) AS cnt FROM categories";
        return $this->conn->query($sql);
    }

    public function jobCountByCategory() {
        $sql = "SELECT c.name AS category_name, COUNT(j.id) AS job_count 
                FROM categories c 
                LEFT JOIN jobs j ON c.id = j.category_id AND j.status = 'active' 
                GROUP BY c.id";
        return $this->conn->query($sql);
    }



}