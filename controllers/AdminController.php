<?php 
require_once '../../models/user.php';
require_once '../../config/db.php';
require_once '../../models/Job.php';
require_once '../../models/Application.php';
require_once '../../models/Category.php';
require_once '../../models/Complaint.php';

class AdminController{

    private $userModel;
    private $jobModel;
    private $applicationModel;
    private $categoryModel;
    private $complaintModel;

    public function __construct(){
        $db = new Database();
        $conn = $db->connect();

        $this->userModel = new User($conn);
        $this->jobModel = new Job($conn);
        $this->applicationModel = new Application($conn);
        $this->categoryModel = new Category($conn);
        $this->complaintModel = new Complaint($conn);
    }

    private function isAjaxFile(){
        return isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/ajax/') !== false;
    }

    public function dashboard(){
        $data = [
            'seeker' => 0,
            'employer' => 0,
            'recruiter' => 0,
            'active_jobs' => 0,
            'recent_applications' => 0,
            'pending_employers' => [],
            'pending_recruiters' => []
        ];

        $userResult = $this->userModel->countByRole();
        while($row = $userResult->fetch_assoc()){
            $data[$row['role']] = $row['cnt'];
        }

        $jobResult = $this->jobModel->countActiveJobs();
        $jobRow = $jobResult->fetch_assoc();
        $data['active_jobs'] = $jobRow['cnt'];

        $applicationResult = $this->applicationModel->countRecentApplications();
        $applicationRow = $applicationResult->fetch_assoc();
        $data['recent_applications'] = $applicationRow['cnt'];

        $pendingEmployerResult = $this->userModel->getPendingEmployers('employer');
        while($row = $pendingEmployerResult->fetch_assoc()){
            $data['pending_employers'][] = $row;
        }

        $pendingRecruiterResult = $this->userModel->getPendingEmployers('recruiter');
        while($row = $pendingRecruiterResult->fetch_assoc()){
            $data['pending_recruiters'][] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

    public function approveUser($user_id){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $result = $this->userModel->approveUser($user_id);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function rejectUser($user_id){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $result = $this->userModel->rejectUser($user_id);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function getUserByRole($role){
        $data = [];

        if($role == 'seeker'){
            $result = $this->userModel->getSeekers('');
        }else if($role == 'employer'){
            $result = $this->userModel->getEmployers('');
        }else if($role == 'recruiter'){
            $result = $this->userModel->getRecruiters('');
        }else{
            $result = $this->userModel->getUserByRole($role);
        }

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

    public function toggleUserStatus($user_id, $status){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            if($status == 1){
                $result = $this->userModel->activateUser($user_id);
            }else{
                $result = $this->userModel->deactivateUser($user_id);
            }

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function getAllCategories(){
        $data = [];
        $result = $this->categoryModel->getAllCategories();

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

    public function addCategory(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if(empty($name)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Category name is required'
                ]);
                return;
            }

            $result = $this->categoryModel->addCategory($name, $description);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function updateCategory(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if(empty($name)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Category name is required'
                ]);
                return;
            }

            $result = $this->categoryModel->updateCategory($id, $name, $description);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function deleteCategory($category_id){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $jobResult = $this->categoryModel->hasJobs($category_id);
            $jobRow = $jobResult->fetch_assoc();

            if($jobRow['cnt'] > 0){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'This category has jobs'
                ]);
                return;
            }

            $result = $this->categoryModel->deleteCategory($category_id);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function getAllJobs(){
        $data = [];
        $result = $this->jobModel->getAllJobs();

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

    public function deleteJob($job_id){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $result = $this->jobModel->deleteJob($job_id);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function toggleFeatured($job_id, $featured){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $result = $this->jobModel->toggleFeatured($job_id, $featured);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function getAllComplaints(){
        $data = [];
        $result = $this->complaintModel->getAllComplaints();

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }

    public function resolveComplaint($complaint_id){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $admin_note = trim($_POST['admin_note']);

            if(empty($admin_note)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Admin note is required'
                ]);
                return;
            }

            $result = $this->complaintModel->resolveComplaint($complaint_id, $admin_note);

            if($result){
                echo json_encode(['status' => 'success']);
            }else{
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function analytics(){
        $data = [
            'jobs_by_category' => [],
            'users_by_role' => [],
            'active_jobs' => 0,
            'application_count' => 0,
            'recruiter_count' => 0,
            'open_complaints' => 0
        ];

        $categoryResult = $this->categoryModel->jobCountByCategory();
        while($row = $categoryResult->fetch_assoc()){
            $data['jobs_by_category'][] = $row;
        }

        $userResult = $this->userModel->countByRole();
        while($row = $userResult->fetch_assoc()){
            $data['users_by_role'][] = $row;

            if($row['role'] == 'recruiter'){
                $data['recruiter_count'] = $row['cnt'];
            }
        }

        $jobResult = $this->jobModel->countActiveJobs();
        $jobRow = $jobResult->fetch_assoc();
        $data['active_jobs'] = $jobRow['cnt'];

        $applicationResult = $this->applicationModel->countAllApplications();
        $applicationRow = $applicationResult->fetch_assoc();
        $data['application_count'] = $applicationRow['cnt'];

        $complaintResult = $this->complaintModel->countOpenComplaints();
        $complaintRow = $complaintResult->fetch_assoc();
        $data['open_complaints'] = $complaintRow['cnt'];

        if($this->isAjaxFile()){
            echo json_encode($data);
        }

        return $data;
    }
}
?>
