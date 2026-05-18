<?php 
require_once '../../models/Recruiter.php';
require_once '../../models/Client.php';
require_once '../../models/Job.php';
require_once '../../models/Application.php';
require_once '../../models/Outreach.php';
require_once '../../models/Category.php';
require_once '../../config/db.php';
require_once '../../models/user.php';

class RecruiterController {
    private $recruiterModel;
    private $clientModel;
    private $jobModel;
    private $applicationModel;
    private $outreachModel;
    private $categoryModel;
    private $userModel;


    public function __construct() {
        $db = new Database();
        $conn = $db->connect();

        $this->recruiterModel = new Recruiter($conn);
        $this->clientModel = new Client($conn);
        $this->jobModel = new Job($conn);
        $this->applicationModel = new Application($conn);
        $this->outreachModel = new Outreach($conn);
        $this->categoryModel = new Category($conn);
        $this->userModel = new User($conn);
    
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

        if(!is_numeric($_POST['category_id'])){
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid category'
            ]);
            return false;
        }

        $categoryResult = $this->categoryModel->getCategoryById($_POST['category_id']);

        if($categoryResult->num_rows == 0){
            echo json_encode([
                'status' => 'error',
                'message' => 'Category not found'
            ]);
            return false;
        }

        if(isset($_POST['employer_id']) && trim($_POST['employer_id']) != ""){
            if(!is_numeric($_POST['employer_id'])){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Employer ID must be a number'
                ]);
                return false;
            }

            $employerResult = $this->userModel->getEmployerById($_POST['employer_id']);

            if($employerResult->num_rows == 0){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Employer ID not found'
                ]);
                return false;
            }
        }

        if($_POST['salary_min'] != "" && $_POST['salary_max'] != "" && $_POST['salary_min'] > $_POST['salary_max']){
            echo json_encode([
                'status' => 'error',
                'message' => 'Minimum salary cannot be greater than maximum salary'
            ]);
            return false;
        }
         if($_POST['salary_max'] != "" && !is_numeric($_POST['salary_max'])){
            echo json_encode([
                'status' => 'error',
                'message' => 'Maximum salary must be a number'
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
        
        $applicationResult = $this->applicationModel->countApplicationsByRecruiter($recruiter_id);
        $applicationRow = $applicationResult->fetch_assoc();
        $data['total_applications'] = $applicationRow['cnt'];

        $outreachResult = $this->outreachModel->countOutreach($recruiter_id);
        $outreachRow = $outreachResult->fetch_assoc();
        $data['total_outreach'] = $outreachRow['cnt'];

        $pipelineResult = $this->applicationModel->getPipelineByRecruiter($recruiter_id);
        while($row = $pipelineResult->fetch_assoc()){
            $data['pipeline'][] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }
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

     public function getClients($recruiter_id){
        $data = [];
        $result = $this->clientModel->getClients($recruiter_id);

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

    public function addClient($recruiter_id){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $employer_id = trim($_POST['employer_id']);
            $company_name_override = trim($_POST['company_name_override']);

            if(empty($employer_id) && empty($company_name_override)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Enter an employer id or company name'
                ]);
                return;
            }

            if(!empty($employer_id)){
                if(!is_numeric($employer_id)){
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Employer ID must be a number'
                    ]);
                    return;
                }

                $employerResult = $this->userModel->getEmployerById($employer_id);

                if($employerResult->num_rows == 0){
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Employer ID not found'
                    ]);
                    return;
                }
            }

            $result = $this->clientModel->addClient($recruiter_id, $employer_id, $company_name_override);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

     public function deleteClient($client_id, $recruiter_id){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $result = $this->clientModel->deleteClient($client_id, $recruiter_id);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function getJobs($recruiter_id){
        $data = [];
        $result = $this->jobModel->getJobsByRecruiter($recruiter_id);

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

    public function getJobById($job_id){
        $result = $this->jobModel->getJobById($job_id);
        return $result->fetch_assoc();
    }
    public function getCategories(){
        $data = [];
        $result = $this->categoryModel->getAllCategories();

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        return $data;
    }
     public function addJob($recruiter_id){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!$this->validateJob()){
                return;
            }

            $data = $this->jobData($recruiter_id);
            $result = $this->jobModel->addJob($data);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function updateJob($job_id, $recruiter_id){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $jobResult = $this->jobModel->getJobById($job_id);
            $job = $jobResult->fetch_assoc();

            if(!$job || $job['recruiter_id'] != $recruiter_id){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Job not found'
                ]);
                return;
            }

            if(!$this->validateJob()){
                return;
            }

            $data = $this->jobData($recruiter_id);
            $result = $this->jobModel->updateJob($job_id, $data);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function deleteJob($job_id, $recruiter_id){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $jobResult = $this->jobModel->getJobById($job_id);
            $job = $jobResult->fetch_assoc();

            if(!$job || $job['recruiter_id'] != $recruiter_id){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Job not found'
                ]);
                return;
            }

            $result = $this->jobModel->deleteJob($job_id);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }
     public function closeJob($job_id, $recruiter_id){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $jobResult = $this->jobModel->getJobById($job_id);
            $job = $jobResult->fetch_assoc();

            if(!$job || $job['recruiter_id'] != $recruiter_id){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Job not found'
                ]);
                return;
            }

            $result = $this->jobModel->closeJob($job_id);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

     public function searchSeekers(){
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $location = isset($_GET['location']) ? $_GET['location'] : '';
        $experience = isset($_GET['experience']) ? $_GET['experience'] : '';
        $salary = isset($_GET['salary']) ? $_GET['salary'] : '';

        $data = [];
        $result = $this->recruiterModel->searchSeekers($keyword, $location, $experience, $salary);

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

}  

?>