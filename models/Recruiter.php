<?php
require_once '../../config/db.php';

class Recruiter{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getProfile($user_id){
        $user_id = $this->conn->real_escape_string($user_id);
        $sql = "SELECT users.name, users.email, recruiter_profiles.*
                FROM users
                LEFT JOIN recruiter_profiles ON users.id = recruiter_profiles.user_id
                WHERE users.id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function updateProfile($user_id, $agency_name, $specialization, $description, $website){
        $user_id = $this->conn->real_escape_string($user_id);
        $agency_name = $this->conn->real_escape_string($agency_name);
        $specialization = $this->conn->real_escape_string($specialization);
        $description = $this->conn->real_escape_string($description);
        $website = $this->conn->real_escape_string($website);

        $check = "SELECT id FROM recruiter_profiles WHERE user_id = '$user_id'";
        $result = $this->conn->query($check);

        if($result->num_rows > 0){
            $sql = "UPDATE recruiter_profiles SET
                    agency_name = '$agency_name',
                    specialization = '$specialization',
                    description = '$description',
                    website = '$website'
                    WHERE user_id = '$user_id'";
        }else{
            $sql = "INSERT INTO recruiter_profiles(user_id, agency_name, specialization, description, website)
                    VALUES('$user_id', '$agency_name', '$specialization', '$description', '$website')";
        }

        return $this->conn->query($sql);
    }

    public function searchSeekers($keyword, $location, $experience, $salary){
        $keyword = $this->conn->real_escape_string($keyword);
        $location = $this->conn->real_escape_string($location);
        $experience = $this->conn->real_escape_string($experience);
        $salary = $this->conn->real_escape_string($salary);

        $sql = "SELECT users.id, users.name, users.email, seeker_profiles.headline, seeker_profiles.skills,
                seeker_profiles.years_experience AS experience, seeker_profiles.expected_salary, seeker_profiles.preferred_location
                FROM users
                LEFT JOIN seeker_profiles ON users.id = seeker_profiles.user_id
                WHERE users.role = 'seeker' AND users.is_active = 1";

        if($keyword != ""){
            $sql .= " AND (users.name LIKE '%$keyword%' OR users.email LIKE '%$keyword%'
                    OR seeker_profiles.headline LIKE '%$keyword%' OR seeker_profiles.skills LIKE '%$keyword%')";
        }

        if($location != ""){
            $sql .= " AND seeker_profiles.preferred_location LIKE '%$location%'";
        }

        if($experience != ""){
            $sql .= " AND seeker_profiles.years_experience >= '$experience'";
        }

        if($salary != ""){
            $sql .= " AND seeker_profiles.expected_salary <= '$salary'";
        }

        $sql .= " ORDER BY users.name ASC";
        return $this->conn->query($sql);
    }

    public function getAnalytics($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT
                (SELECT COUNT(*) FROM jobs WHERE recruiter_id = '$recruiter_id') AS total_jobs,
                (SELECT COUNT(*) FROM jobs WHERE recruiter_id = '$recruiter_id' AND status = 'active') AS active_jobs,
                (SELECT COUNT(*) FROM recruiter_clients WHERE recruiter_id = '$recruiter_id') AS total_clients,
                (SELECT COUNT(*) FROM recruiter_outreach WHERE recruiter_id = '$recruiter_id') AS total_outreach";
        return $this->conn->query($sql);
    }
}
?>
