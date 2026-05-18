<?php 
require_once '../../models/Recruiter.php';
require_once '../../models/Client.php';
require_once '../../models/Job.php';
class RecruiterController {
    private $recruiterModel;
    private $clientModel;
    private $jobModel;

    public function __construct() {
        $db = new Database();
        $conn = $db->connect();

        $this->recruiterModel = new Recruiter($conn);
        $this->clientModel = new Client($conn);
        $this->jobModel = new Job($conn);
    
    }

    private function isAjaxFile(){
        return isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/ajax/') !== false;
    }

     private function jobData($recruiter_id){
        return [
            'recruiter_id' => $recruiter_id,
            'employer_id' => isset($_POST['employer_id']) ? $_POST['employer_id'] : '',
            'category_id' => $_POST['category_id'],
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'requirements' => trim($_POST['requirements']),
            'benefits' => trim($_POST['benefits']),
            'salary_min' => $_POST['salary_min'],
            'salary_max' => $_POST['salary_max'],
            'location' => trim($_POST['location']),
            'job_type' => $_POST['job_type'],
            'experience_level' => $_POST['experience_level'],
            'deadline' => $_POST['deadline'],
            'status' => $_POST['status']
        ];
    }
    private function validateJob(){
        if(empty($_POST['category_id']) || empty($_POST['title']) || empty($_POST['description']) || empty($_POST['deadline'])){
            echo json_encode([
                'status' => 'error',
                'message' => 'Category, title, description and deadline are required'
            ]);
            return false;
        }

        if($_POST['salary_min'] != "" && $_POST['salary_max'] != "" && $_POST['salary_min'] > $_POST['salary_max']){
            echo json_encode([
                'status' => 'error',
                'message' => 'Minimum salary cannot be greater than maximum salary'
            ]);
            return false;
        }

        return true;
    }
    public function dashboard($recruiter_id){
        $data = [
            'total_clients' => 0,
            'total_jobs' => 0,
            'active_jobs' => 0,
            'total_applications' => 0,
            'total_outreach' => 0,
            'pipeline' => []
        ];

        $clientResult = $this->clientModel->countClients($recruiter_id);
        $clientRow = $clientResult->fetch_assoc();
        $data['total_clients'] = $clientRow['cnt'];

        $jobResult = $this->jobModel->countJobsByRecruiter($recruiter_id);
        $jobRow = $jobResult->fetch_assoc();
        $data['total_jobs'] = $jobRow['cnt'];

        $activeJobResult = $this->jobModel->countActiveJobsByRecruiter($recruiter_id);
        $activeJobRow = $activeJobResult->fetch_assoc();
        $data['active_jobs'] = $activeJobRow['cnt'];
        return $data;
    }
     public function profile($recruiter_id){
        $result = $this->recruiterModel->getProfile($recruiter_id);
        $profile = $result->fetch_assoc();

        if($this->isAjaxFile()){
            echo json_encode($profile);
        }

        return $profile;
    }
    public function updateProfile($recruiter_id){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $agency_name = trim($_POST['agency_name']);
            $specialization = trim($_POST['specialization']);
            $description = trim($_POST['description']);
            $website = trim($_POST['website']);

            if(empty($agency_name)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Agency name is required'
                ]);
                return;
            }

            $result = $this->recruiterModel->updateProfile($recruiter_id, $agency_name, $specialization, $description, $website);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

}  

?>