<?php
require_once '../../config/db.php';

class Job{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function countActiveJobs(){
        $sql = "SELECT COUNT(*) AS cnt FROM jobs WHERE status = 'active'";
        return $this->conn->query($sql);
    }

    public function getAllJobs(){
        $sql = "SELECT jobs.*, categories.name AS category_name, recruiters.name AS recruiter_name,
                employers.name AS employer_name
                FROM jobs
                LEFT JOIN categories ON jobs.category_id = categories.id
                LEFT JOIN users recruiters ON jobs.recruiter_id = recruiters.id
                LEFT JOIN users employers ON jobs.employer_id = employers.id
                ORDER BY jobs.id DESC";
        return $this->conn->query($sql);
    }

    public function getJobsByRecruiter($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT jobs.*, categories.name AS category_name
                FROM jobs
                LEFT JOIN categories ON jobs.category_id = categories.id
                WHERE jobs.recruiter_id = '$recruiter_id'
                ORDER BY jobs.id DESC";
        return $this->conn->query($sql);
    }

    public function getJobById($job_id){
        $job_id = $this->conn->real_escape_string($job_id);
        $sql = "SELECT * FROM jobs WHERE id = '$job_id'";
        return $this->conn->query($sql);
    }

    public function addJob($data){
        $recruiter_id = $this->conn->real_escape_string($data['recruiter_id']);
        $employer_id = $this->conn->real_escape_string($data['employer_id']);
        $category_id = $this->conn->real_escape_string($data['category_id']);
        $title = $this->conn->real_escape_string($data['title']);
        $description = $this->conn->real_escape_string($data['description']);
        $requirements = $this->conn->real_escape_string($data['requirements']);
        $benefits = $this->conn->real_escape_string($data['benefits']);
        $salary_min = $this->conn->real_escape_string($data['salary_min']);
        $salary_max = $this->conn->real_escape_string($data['salary_max']);
        $location = $this->conn->real_escape_string($data['location']);
        $job_type = $this->conn->real_escape_string($data['job_type']);
        $experience_level = $this->conn->real_escape_string($data['experience_level']);
        $deadline = $this->conn->real_escape_string($data['deadline']);
        $status = $this->conn->real_escape_string($data['status']);

        if($employer_id == ""){
            $employer_id = "NULL";
        }else{
            $employer_id = "'$employer_id'";
        }

        $sql = "INSERT INTO jobs(recruiter_id, employer_id, category_id, title, description, requirements, benefits,
                salary_min, salary_max, location, job_type, experience_level, deadline, status)
                VALUES('$recruiter_id', $employer_id, '$category_id', '$title', '$description', '$requirements',
                '$benefits', '$salary_min', '$salary_max', '$location', '$job_type', '$experience_level',
                '$deadline', '$status')";
        return $this->conn->query($sql);
    }

    public function updateJob($job_id, $data){
        $job_id = $this->conn->real_escape_string($job_id);
        $employer_id = $this->conn->real_escape_string($data['employer_id']);
        $category_id = $this->conn->real_escape_string($data['category_id']);
        $title = $this->conn->real_escape_string($data['title']);
        $description = $this->conn->real_escape_string($data['description']);
        $requirements = $this->conn->real_escape_string($data['requirements']);
        $benefits = $this->conn->real_escape_string($data['benefits']);
        $salary_min = $this->conn->real_escape_string($data['salary_min']);
        $salary_max = $this->conn->real_escape_string($data['salary_max']);
        $location = $this->conn->real_escape_string($data['location']);
        $job_type = $this->conn->real_escape_string($data['job_type']);
        $experience_level = $this->conn->real_escape_string($data['experience_level']);
        $deadline = $this->conn->real_escape_string($data['deadline']);
        $status = $this->conn->real_escape_string($data['status']);

        if($employer_id == ""){
            $employer_sql = "employer_id = NULL";
        }else{
            $employer_sql = "employer_id = '$employer_id'";
        }

        $sql = "UPDATE jobs SET
                $employer_sql,
                category_id = '$category_id',
                title = '$title',
                description = '$description',
                requirements = '$requirements',
                benefits = '$benefits',
                salary_min = '$salary_min',
                salary_max = '$salary_max',
                location = '$location',
                job_type = '$job_type',
                experience_level = '$experience_level',
                deadline = '$deadline',
                status = '$status'
                WHERE id = '$job_id'";
        return $this->conn->query($sql);
    }

    public function deleteJob($job_id){
        $job_id = $this->conn->real_escape_string($job_id);
        $sql = "DELETE FROM jobs WHERE id = '$job_id'";
        return $this->conn->query($sql);
    }

    public function closeJob($job_id){
        $job_id = $this->conn->real_escape_string($job_id);
        $sql = "UPDATE jobs SET status = 'closed' WHERE id = '$job_id'";
        return $this->conn->query($sql);
    }

    public function toggleFeatured($job_id, $featured){
        $job_id = $this->conn->real_escape_string($job_id);
        $featured = $this->conn->real_escape_string($featured);
        $sql = "UPDATE jobs SET is_featured = '$featured' WHERE id = '$job_id'";
        return $this->conn->query($sql);
    }

    public function countJobsByRecruiter($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT COUNT(*) AS cnt FROM jobs WHERE recruiter_id = '$recruiter_id'";
        return $this->conn->query($sql);
    }

    public function countActiveJobsByRecruiter($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT COUNT(*) AS cnt FROM jobs WHERE recruiter_id = '$recruiter_id' AND status = 'active'";
        return $this->conn->query($sql);
    }
}
?>
