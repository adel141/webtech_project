<?php
session_start();

if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: views/admin/dashboard.php");
        exit;
    }

    if($_SESSION['role'] == 'recruiter'){
        header("Location: views/recruiter/dashboard.php");
        exit;
    }
}

header("Location: login.php");
exit;
?>
