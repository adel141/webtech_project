<?php
require_once '../../config/db.php';

class Outreach{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function sendOutreach($recruiter_id, $seeker_id, $job_id, $message){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $seeker_id = $this->conn->real_escape_string($seeker_id);
        $job_id = $this->conn->real_escape_string($job_id);
        $message = $this->conn->real_escape_string($message);

        $sql = "INSERT INTO recruiter_outreach(recruiter_id, seeker_id, job_id, message)
                VALUES('$recruiter_id', '$seeker_id', '$job_id', '$message')";
        return $this->conn->query($sql);
    }

    public function getOutreachByRecruiter($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT recruiter_outreach.*, recruiter_outreach.sent_at AS created_at,
                seekers.name AS seeker_name, jobs.title AS job_title
                FROM recruiter_outreach
                LEFT JOIN users seekers ON recruiter_outreach.seeker_id = seekers.id
                LEFT JOIN jobs ON recruiter_outreach.job_id = jobs.id
                WHERE recruiter_outreach.recruiter_id = '$recruiter_id'
                ORDER BY recruiter_outreach.id DESC";
        return $this->conn->query($sql);
    }

    public function countOutreach($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT COUNT(*) AS cnt FROM recruiter_outreach WHERE recruiter_id = '$recruiter_id'";
        return $this->conn->query($sql);
    }
}
?>
