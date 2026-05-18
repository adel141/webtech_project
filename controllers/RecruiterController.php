<?php
require_once "../../models/Job.php";
require_once "../../config/db.php";
require_once "../../models/client.php";
require_once "../../models/outreach.php";
require_once "../../models/responded.php";
require_once "../../models/recentOutreach.php";

Class RecruiterController {
    private $jobModel;
    private $db;

    public function __construct() {
        $this->db = new Database();
        $db = $this->db->connect();
        $this->jobModel = new Job($db);
        $this->clientModel = new Client($db);
        $this->outreachModel = new Outreach($db);
        $this->respondedModel = new Responded($db);
        $this->recentOutreachModel = new recentOutreach($db);

    }

    public function loadDashboard(){
        $data=[];
        $activejobsResult = $this->jobModel->countActiveJobs()->fetch_assoc();
        $CLIENTSResult = $this->clientModel->countClients(3)->fetch_assoc();
        $OUTREACHResult = $this->outreachModel->countOutreach(3)->fetch_assoc();
        $data['active_jobs'] = $activejobsResult['COUNT(*)'] ?? 0;
        $data['clients'] = $CLIENTSResult['COUNT(*)'] ?? 0;
        $data['outreach'] = $OUTREACHResult['COUNT(*)'] ?? 0;
        $data['responded'] = $this->respondedModel->countResponded()->fetch_assoc()['COUNT(*)'] ?? 0;
        echo json_encode($data);
    }
    public function loadRecentOutreach(){
        $outreachData = [];
        $result = $this->recentOutreachModel->getRecentOutreach(3);
       
        while($row = $result->fetch_assoc()){
            $outreachData[] = $row;
        }
        echo json_encode($outreachData);
    }

     public function clients($post = null) {
        $recruiterId = $this->recruiterId();
        if ($post !== null) {
            $ok = $this->clientModel->add($recruiterId, $post['employer_id'] ?? '', trim($post['company_name_override'] ?? ''));
            $this->json(['status' => $ok ? 'success' : 'error'], $ok ? 200 : 422);
            return;
        }
        $this->json([
            'clients' => $this->clientModel->all($recruiterId),
            'employers' => $this->clientModel->employers()
        ]);
    }

     private function recruiterId() {
        return 3;
     }



}

?>