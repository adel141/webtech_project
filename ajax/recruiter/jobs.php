<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->getJobs($_SESSION['user_id']);
?>
