<?php 
require_once '../../models/user.php';
require_once '../../config/db.php';

class AuthController{

    private $userModel;

    public function __construct(){
        $db = new Database();
        $conn = $db->connect();

        $this->userModel = new User($conn);
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

                if($user['role'] != 'admin' && $user['role'] != 'recruiter'){
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Only admin and recruiter accounts can login here'
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

                if($user['is_approved'] != 1){
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Your account is waiting for admin approval'
                    ]);
                    return;
                }

                if($user['is_active'] == 1 && $user['is_approved'] == 1){
                    if(session_status() == PHP_SESSION_NONE){
                        session_start();
                    }

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    echo json_encode([
                        'status' => 'success',
                        'role' => $user['role']
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
            $phone = trim($_POST['phone']);
            $agency_name = trim($_POST['agency_name']);
            $specialization = trim($_POST['specialization']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if(empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($agency_name)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Name, email, agency name and password are required'
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
            $result = $this->userModel->registerRecruiter($name, $email, $password_hash, $phone, $agency_name, $specialization);

            if($result){
                setcookie("jp_registered_email", $email, time() + (86400 * 7), "/");
                setcookie("jp_registered_name", $name, time() + (86400 * 7), "/");
                setcookie("jp_register_success", "1", time() + 300, "/");

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Registration successful. Please wait for admin approval.',
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

        header("Location: ../../login.php");
    }
}
?>
