<?php 
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../config/db.php';

class AuthController{

    private $userModel;

    public function __construct(){
        $db = new Database();
        $conn = $db->connect();

        $this->userModel = new User($conn);
    }

    private function dashboardPath($role){
        $paths = [
            'admin' => 'views/admin/dashboard.php',
            'recruiter' => 'views/recruiter/dashboard.php',
            'employer' => 'public/index.php/employer/dashboard',
            'seeker' => 'public/index.php/seeker/dashboard'
        ];

        return $paths[$role] ?? 'login.php';
    }

    private function rootPath($file){
        if(defined('PUBLIC_URL')){
            return rtrim(dirname(PUBLIC_URL), '/\\') . '/' . $file;
        }

        if(defined('BASE_URL')){
            return rtrim(dirname(dirname(BASE_URL)), '/\\') . '/' . $file;
        }

        return '../../' . $file;
    }

    public function home(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        $role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';
        if(isset($_SESSION['user_id']) && $role != ''){
            header("Location: " . $this->rootPath($this->dashboardPath($role)));
            exit;
        }

        header("Location: " . $this->rootPath('login.php'));
        exit;
    }

    public function loginForm(){
        header("Location: " . $this->rootPath('login.php'));
        exit;
    }

    public function registerForm(){
        header("Location: " . $this->rootPath('register.php'));
        exit;
    }

    public function login(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if(empty($email) || empty($password)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email and password are required'
                ]);
                return;
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Enter a valid email address'
                ]);
                return;
            }

            $result = $this->userModel->login($email);

            if($result->num_rows == 1){
                $user = $result->fetch_assoc();

                if(!password_verify($password, $user['password'])){
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Invalid email or password'
                    ]);
                    return;
                }

                if($user['is_active'] != 1){
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Your account is inactive'
                    ]);
                    return;
                }

                if(in_array($user['role'], ['employer', 'recruiter']) && $user['is_approved'] != 1){
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Your account is waiting for admin approval'
                    ]);
                    return;
                }

                if($user['is_active'] == 1){
                    if(session_status() == PHP_SESSION_NONE){
                        session_start();
                    }

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_pic'] = $user['profile_pic'] ?? null;
                    $_SESSION['is_verified'] = $user['is_approved'];

                    echo json_encode([
                        'status' => 'success',
                        'role' => $user['role'],
                        'redirect' => $this->dashboardPath($user['role'])
                    ]);
                    return;
                }
            }

            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ]);
        }
    }

    public function register(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone'] ?? '');
            $role = trim($_POST['role'] ?? 'seeker');
            $headline = trim($_POST['headline'] ?? '');
            $skills = trim($_POST['skills'] ?? '');
            $company_name = trim($_POST['company_name'] ?? '');
            $industry = trim($_POST['industry'] ?? '');
            $company_size = trim($_POST['company_size'] ?? '');
            $agency_name = trim($_POST['agency_name'] ?? '');
            $specialization = trim($_POST['specialization'] ?? '');
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if(!in_array($role, ['seeker', 'employer', 'recruiter'])){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Select a valid account type'
                ]);
                return;
            }

            if(empty($name) || empty($email) || empty($password) || empty($confirm_password)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Name, email and password are required'
                ]);
                return;
            }

            if($role == 'employer' && empty($company_name)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Company name is required for employers'
                ]);
                return;
            }

            if($role == 'recruiter' && empty($agency_name)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Agency name is required for recruiters'
                ]);
                return;
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Enter a valid email address'
                ]);
                return;
            }

            if($password != $confirm_password){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Passwords do not match'
                ]);
                return;
            }

            if(strlen($password) < 6){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Password must be at least 6 characters'
                ]);
                return;
            }

            $checkEmail = $this->userModel->emailExists($email);

            if($checkEmail->num_rows > 0){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email already exists'
                ]);
                return;
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $profileData = [
                'headline' => $headline,
                'skills' => $skills,
                'company_name' => $company_name,
                'industry' => $industry,
                'company_size' => $company_size,
                'agency_name' => $agency_name,
                'specialization' => $specialization
            ];
            $result = $this->userModel->registerUser($name, $email, $password_hash, $phone, $role, $profileData);

            if($result){
                setcookie("jp_registered_email", $email, time() + (86400 * 7), "/");
                setcookie("jp_registered_name", $name, time() + (86400 * 7), "/");
                setcookie("jp_register_success", "1", time() + 300, "/");
                setcookie("jp_registered_role", $role, time() + 300, "/");

                $message = 'Registration successful. You can login now.';
                if(in_array($role, ['employer', 'recruiter'])){
                    $message = 'Registration successful. Please wait for admin approval.';
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => $message,
                    'redirect' => 'login.php'
                ]);
            }else{
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Registration failed'
                ]);
            }
        }
    }

    public function logout(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        session_destroy();
        setcookie("jp_remember_id", "", time() - 3600, "/");
        setcookie("jp_remember_token", "", time() - 3600, "/");

        header("Location: " . $this->rootPath('login.php'));
    }
}
?>
