<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->closeJob($_GET['job_id'], $_SESSION['user_id']);
?>
