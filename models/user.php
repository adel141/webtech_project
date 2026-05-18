<?php
require_once __DIR__ . '/../config/db.php';

class User{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function login($email){
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT users.*, users.password_hash AS password, users.is_verified AS is_approved
                FROM users
                WHERE email = '$email'
                LIMIT 1";
        return $this->conn->query($sql);
    }

    public function emailExists($email){
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        return $this->conn->query($sql);
    }

    public function registerUser($name, $email, $password, $phone, $role, $profileData = []){
        if(!in_array($role, ['seeker', 'employer', 'recruiter'])){
            return false;
        }

        $is_verified = $role == 'seeker' ? 1 : 0;
        $is_active = 1;

        $this->conn->begin_transaction();

        try{
            $stmt = $this->conn->prepare(
                "INSERT INTO users(name, email, password_hash, phone, role, is_active, is_verified)
                 VALUES(?, ?, ?, ?, ?, ?, ?)"
            );
            if(!$stmt){
                throw new Exception($this->conn->error);
            }

            $stmt->bind_param("sssssii", $name, $email, $password, $phone, $role, $is_active, $is_verified);
            if(!$stmt->execute()){
                throw new Exception($stmt->error);
            }

            $user_id = $stmt->insert_id;
            $stmt->close();

            if($role == 'seeker'){
                $headline = $profileData['headline'] ?? '';
                $skills = $profileData['skills'] ?? '';

                $stmt = $this->conn->prepare(
                    "INSERT INTO seeker_profiles(user_id, headline, skills)
                     VALUES(?, ?, ?)"
                );
                if(!$stmt){
                    throw new Exception($this->conn->error);
                }
                $stmt->bind_param("iss", $user_id, $headline, $skills);
            }elseif($role == 'employer'){
                $company_name = $profileData['company_name'] ?? '';
                $industry = $profileData['industry'] ?? '';
                $company_size = $profileData['company_size'] ?? '';

                $stmt = $this->conn->prepare(
                    "INSERT INTO employer_profiles(user_id, company_name, industry, company_size)
                     VALUES(?, ?, ?, ?)"
                );
                if(!$stmt){
                    throw new Exception($this->conn->error);
                }
                $stmt->bind_param("isss", $user_id, $company_name, $industry, $company_size);
            }else{
                $agency_name = $profileData['agency_name'] ?? '';
                $specialization = $profileData['specialization'] ?? '';

                $stmt = $this->conn->prepare(
                    "INSERT INTO recruiter_profiles(user_id, agency_name, specialization)
                     VALUES(?, ?, ?)"
                );
                if(!$stmt){
                    throw new Exception($this->conn->error);
                }
                $stmt->bind_param("iss", $user_id, $agency_name, $specialization);
            }

            if(!$stmt->execute()){
                throw new Exception($stmt->error);
            }
            $stmt->close();

            $this->conn->commit();
            return true;
        }catch(Exception $e){
            $this->conn->rollback();
            return false;
        }
    }

    public function registerRecruiter($name, $email, $password, $phone, $agency_name, $specialization){
        return $this->registerUser($name, $email, $password, $phone, 'recruiter', [
            'agency_name' => $agency_name,
            'specialization' => $specialization
        ]);
    }

    public function countByRole(){
        $sql = "SELECT role, COUNT(*) AS cnt FROM users WHERE is_active = 1 GROUP BY role";
        return $this->conn->query($sql);
    }

    public function getPendingEmployers($role){
        $role = $this->conn->real_escape_string($role);
        $sql = "SELECT users.*, users.is_verified AS is_approved
                FROM users
                WHERE role = '$role' AND is_verified = 0
                ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function approveUser($user_id){
        $user_id = $this->conn->real_escape_string($user_id);
        $sql = "UPDATE users SET is_verified = 1, is_active = 1 WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function rejectUser($user_id){
        $user_id = $this->conn->real_escape_string($user_id);
        $sql = "UPDATE users SET is_verified = 0, is_active = 0 WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function deactivateUser($user_id){
        $user_id = $this->conn->real_escape_string($user_id);
        $sql = "UPDATE users SET is_active = 0 WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function activateUser($user_id){
        $user_id = $this->conn->real_escape_string($user_id);
        $sql = "UPDATE users SET is_active = 1, is_verified = 1 WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function getUserByRole($role){
        $role = $this->conn->real_escape_string($role);
        $sql = "SELECT users.*, users.is_verified AS is_approved
                FROM users
                WHERE role = '$role'
                ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function getSeekers($keyword){
        $keyword = $this->conn->real_escape_string($keyword);
        $sql = "SELECT users.*, seeker_profiles.headline, seeker_profiles.skills,
                seeker_profiles.years_experience AS experience,
                seeker_profiles.expected_salary, seeker_profiles.preferred_location,
                users.is_verified AS is_approved
                FROM users
                LEFT JOIN seeker_profiles ON users.id = seeker_profiles.user_id
                WHERE users.role = 'seeker'
                AND (
                    users.name LIKE '%$keyword%'
                    OR users.email LIKE '%$keyword%'
                    OR seeker_profiles.headline LIKE '%$keyword%'
                    OR seeker_profiles.skills LIKE '%$keyword%'
                )
                ORDER BY users.id DESC";
        return $this->conn->query($sql);
    }

    public function getEmployers($keyword){
        $keyword = $this->conn->real_escape_string($keyword);
        $sql = "SELECT users.*, employer_profiles.company_name, employer_profiles.industry, employer_profiles.website,
                users.is_verified AS is_approved
                FROM users
                LEFT JOIN employer_profiles ON users.id = employer_profiles.user_id
                WHERE users.role = 'employer'
                AND (
                    users.name LIKE '%$keyword%'
                    OR users.email LIKE '%$keyword%'
                    OR employer_profiles.company_name LIKE '%$keyword%'
                )
                ORDER BY users.id DESC";
        return $this->conn->query($sql);
    }

    public function getRecruiters($keyword){
        $keyword = $this->conn->real_escape_string($keyword);
        $sql = "SELECT users.*, recruiter_profiles.agency_name, recruiter_profiles.specialization, recruiter_profiles.website,
                users.is_verified AS is_approved
                FROM users
                LEFT JOIN recruiter_profiles ON users.id = recruiter_profiles.user_id
                WHERE users.role = 'recruiter'
                AND (
                    users.name LIKE '%$keyword%'
                    OR users.email LIKE '%$keyword%'
                    OR recruiter_profiles.agency_name LIKE '%$keyword%'
                )
                ORDER BY users.id DESC";
        return $this->conn->query($sql);
    }

    public function getUserById($user_id){
        $user_id = $this->conn->real_escape_string($user_id);
        $sql = "SELECT users.*, users.is_verified AS is_approved FROM users WHERE id = '$user_id'";
        return $this->conn->query($sql);
    }

    public function getEmployerById($employer_id){
        $employer_id = $this->conn->real_escape_string($employer_id);
        $sql = "SELECT users.*, users.is_verified AS is_approved
                FROM users
                WHERE id = '$employer_id' AND role = 'employer'
                LIMIT 1";
        return $this->conn->query($sql);
    }

    public function getSeekerById($seeker_id){
        $seeker_id = $this->conn->real_escape_string($seeker_id);
        $sql = "SELECT users.*, users.is_verified AS is_approved
                FROM users
                WHERE id = '$seeker_id' AND role = 'seeker'
                LIMIT 1";
        return $this->conn->query($sql);
    }
}
?>
