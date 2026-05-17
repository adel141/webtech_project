<?php
require_once "../../models/Job.php";
require_once "../../config/db.php";
require_once "../../models/client.php";

Class RecruiterController {
    private $jobModel;
    private $db;

    public function __construct() {
        $this->db = new Database();
        $db = $this->db->connect();
        $this->jobModel = new Job($db);
        $this->clientModel = new Client($db);
    }

    public function loadDashboard(){
        $data=[];
        $activejobsResult = $this->jobModel->countActiveJobs()->fetch_assoc();
        $CLIENTSResult = $this->clientModel->countClients(3)->fetch_assoc();
        $data['active_jobs'] = $activejobsResult['COUNT(*)'] ?? 0;
        $data['clients'] = $CLIENTSResult['COUNT(*)'] ?? 0;
        echo json_encode($data);
    }

}



?>