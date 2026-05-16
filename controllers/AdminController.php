<?php 
require_once '../../models/user.php';
require_once '../../config/db.php';
require_once '../../models/Job.php';
require_once '../../models/Application.php';
class AdminController{

    private $userModel;
    public function __construct(){
        $db = new Database();
        $conn = $db->connect();
        $this->userModel = new User($conn);
        $this->jobModel = new Job($conn);
        $this->applicationModel = new Application($conn);
    }

    public function dashboard() {
        $userResult = $this->userModel->countByRole();
        $jobResult = $this->jobModel->countActiveJobs(); 
        $applicationResult = $this->applicationModel->countRecentApplications();
        $pendingEmployers = $this->userModel->getPendingEmployers('employer');
        $pendingRecruiters = $this->userModel->getPendingEmployers('recruiter');
       
        $data = [];
        while($row = $userResult->fetch_assoc()){
            $data[] = $row;
        }
        $data['active_jobs'] = $jobResult->fetch_assoc();
        $data['recent_applications'] = $applicationResult->fetch_assoc();

        // echo json_encode($data);

        require_once '../../views/admin/dashboard.php';
        
    }

    public function approverUser($user_id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $result = $this->userModel->approveUser($user_id);
            if($result){
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            
        }

    }
}