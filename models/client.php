<?php
require_once '../../config/db.php';

class Client{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getClients($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT recruiter_clients.*, recruiter_clients.added_at AS created_at,
                users.name AS employer_name, users.email AS employer_email,
                employer_profiles.company_name
                FROM recruiter_clients
                LEFT JOIN users ON recruiter_clients.employer_id = users.id
                LEFT JOIN employer_profiles ON users.id = employer_profiles.user_id
                WHERE recruiter_clients.recruiter_id = '$recruiter_id'
                ORDER BY recruiter_clients.id DESC";
        return $this->conn->query($sql);
    }

    public function addClient($recruiter_id, $employer_id, $company_name_override){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $employer_id = $this->conn->real_escape_string($employer_id);
        $company_name_override = $this->conn->real_escape_string($company_name_override);

        if($employer_id == ""){
            $employer_id = "NULL";
        }else{
            $employer_id = "'$employer_id'";
        }

        $sql = "INSERT INTO recruiter_clients(recruiter_id, employer_id, company_name_override)
                VALUES('$recruiter_id', $employer_id, '$company_name_override')";
        return $this->conn->query($sql);
    }

    public function deleteClient($client_id, $recruiter_id){
        $client_id = $this->conn->real_escape_string($client_id);
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "DELETE FROM recruiter_clients WHERE id = '$client_id' AND recruiter_id = '$recruiter_id'";
        return $this->conn->query($sql);
    }

    public function countClients($recruiter_id){
        $recruiter_id = $this->conn->real_escape_string($recruiter_id);
        $sql = "SELECT COUNT(*) AS cnt FROM recruiter_clients WHERE recruiter_id = '$recruiter_id'";
        return $this->conn->query($sql);
    }
}
?>
