<?php 
require_once '../../models/user.php';
require_once '../../config/db.php';
require_once '../../models/Job.php';
require_once '../../models/Application.php';
require_once '../../models/Category.php';
class AdminController{

    private $userModel;
    public function __construct(){
        $db = new Database();
        $conn = $db->connect();
        $this->userModel = new User($conn);
        $this->jobModel = new Job($conn);
        $this->applicationModel = new Application($conn);
        $this->categoryModel = new Category($conn);

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
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $result = $this->userModel->approveUser($user_id);
            if($result){
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            
        }

    }

    public function getUserByRole($role) {
        $result = $this->userModel->getUserByRole($role);
        $user = [];
        while($row = $result->fetch_assoc()){
            $user[] = $row;
        }
        echo json_encode($user);
    }

    public function toggleUserStatus($user_id, $status) {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if($status == 1){
                $result = $this->userModel->activateUser($user_id);
            } else {
                $result = $this->userModel->deactivateUser($user_id);
            }
            
            if($result){
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
         
    }


        

    function getAllCategories() {
            $result = $this->categoryModel->getAllCategories();
            $jobcountresult = $this->categoryModel->jobCountByCategory();   
            $categoriescount = $this->categoryModel->countCategory();


            $categories = [];
            while($row = $result->fetch_assoc()){
                $categories[] = $row;

            }
            while($row = $jobcountresult->fetch_assoc()){
                $category_name[] = $row;
            }

            $totalCategory = $categoriescount->fetch_assoc()['cnt'];


            $sumCategories['categories'] = $categories; 
            $sumCategories['name'] = $category_name;
            $sumCategories['total'] = $totalCategory;

            echo json_encode($sumCategories);
        }
}