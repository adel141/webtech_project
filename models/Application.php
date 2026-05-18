<?php
require_once '../../config/db.php';

class Application{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function countRecentApplications(){
        $sql = "SELECT COUNT(*) AS cnt FROM applications WHERE applied_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        return $this->conn->query($sql);
    }

    public function countAllApplications(){
        $sql = "SELECT COUNT(*) AS cnt FROM applications";
        return $this->conn->query($sql);
    }

    public function getApplicationsByRecruiter($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT applications.*, applications.applied_at AS created_at,
                jobs.title AS job_title, users.name AS seeker_name, users.email AS seeker_email
                FROM applications
                INNER JOIN jobs ON applications.job_id = jobs.id
                INNER JOIN users ON applications.seeker_id = users.id
                WHERE jobs.recruiter_id = '$recruiter_id'
                ORDER BY applications.id DESC";
        return $this->conn->query($sql);
    }

    public function updateStatus($application_id, $status){
        $application_id = $this->conn->real_escape_string($application_id);
        $status = $this->conn->real_escape_string($status);
        $sql = "UPDATE applications SET status = '$status' WHERE id = '$application_id'";
        return $this->conn->query($sql);
    }

    public function applicationBelongsToRecruiter($application_id, $recruiter_id){
        $application_id = $this->conn->real_escape_string($application_id);
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT COUNT(*) AS cnt
                FROM applications
                INNER JOIN jobs ON applications.job_id = jobs.id
                WHERE applications.id = '$application_id' AND jobs.recruiter_id = '$recruiter_id'";
        return $this->conn->query($sql);
    }

    public function countApplicationsByRecruiter($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT COUNT(*) AS cnt
                FROM applications
                INNER JOIN jobs ON applications.job_id = jobs.id
                WHERE jobs.recruiter_id = '$recruiter_id'";
        return $this->conn->query($sql);
    }

    public function getPipelineByRecruiter($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT applications.status, COUNT(*) AS cnt
                FROM applications
                INNER JOIN jobs ON applications.job_id = jobs.id
                WHERE jobs.recruiter_id = '$recruiter_id'
                GROUP BY applications.status";
        return $this->conn->query($sql);
    }

    public function getApplicationsByJob($job_id){
        $job_id = $this->conn->real_escape_string($job_id);
        $sql = "SELECT applications.*, applications.applied_at AS created_at,
                users.name AS seeker_name, users.email AS seeker_email
                FROM applications
                INNER JOIN users ON applications.seeker_id = users.id
                WHERE applications.job_id = '$job_id'
                ORDER BY applications.id DESC";
        return $this->conn->query($sql);
    }
}
?>
