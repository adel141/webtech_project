<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->deleteJob($_GET['job_id'], $_SESSION['user_id']);
?>
