<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->getApplications($_SESSION['user_id']);
?>
