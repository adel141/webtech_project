<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->updateJob($_GET['job_id'], $_SESSION['user_id']);
?>