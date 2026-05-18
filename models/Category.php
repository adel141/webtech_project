<?php
require_once '../../config/db.php';

class Category{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllCategories(){
        $sql = "SELECT categories.*, COUNT(jobs.id) AS job_count
                FROM categories
                LEFT JOIN jobs ON categories.id = jobs.category_id
                GROUP BY categories.id
                ORDER BY categories.name ASC";
        return $this->conn->query($sql);
    }

    public function getCategoryById($id){
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM categories WHERE id = '$id' LIMIT 1";
        return $this->conn->query($sql);
    }

    public function addCategory($name, $description){
        $name = $this->conn->real_escape_string($name);
        $description = $this->conn->real_escape_string($description);
        $sql = "INSERT INTO categories(name, description) VALUES('$name', '$description')";
        return $this->conn->query($sql);
    }

    public function updateCategory($id, $name, $description){
        $id = $this->conn->real_escape_string($id);
        $name = $this->conn->real_escape_string($name);
        $description = $this->conn->real_escape_string($description);
        $sql = "UPDATE categories SET name = '$name', description = '$description' WHERE id = '$id'";
        return $this->conn->query($sql);
    }

    public function deleteCategory($id){
        $id = $this->conn->real_escape_string($id);
        $sql = "DELETE FROM categories WHERE id = '$id'";
        return $this->conn->query($sql);
    }

    public function countCategory(){
        $sql = "SELECT COUNT(*) AS cnt FROM categories";
        return $this->conn->query($sql);
    }

    public function jobCountByCategory(){
        $sql = "SELECT categories.name, COUNT(jobs.id) AS cnt
                FROM categories
                LEFT JOIN jobs ON categories.id = jobs.category_id
                GROUP BY categories.id
                ORDER BY categories.name ASC";
        return $this->conn->query($sql);
    }

    public function hasJobs($id){
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT COUNT(*) AS cnt FROM jobs WHERE category_id = '$id'";
        return $this->conn->query($sql);
    }
}
?>
