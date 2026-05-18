<?php
session_start();

if(isset($_SESSION['user_id'])){
    $role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';

    if($role == 'admin'){
        header("Location: views/admin/dashboard.php");
        exit;
    }

    if($role == 'recruiter'){
        header("Location: views/recruiter/dashboard.php");
        exit;
    }

    if($role == 'employer'){
        header("Location: public/index.php/employer/dashboard");
        exit;
    }

    if($role == 'seeker'){
        header("Location: public/index.php/seeker/dashboard");
        exit;
    }
}

header("Location: login.php");
exit;
?>
