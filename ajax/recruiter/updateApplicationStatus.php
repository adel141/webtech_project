<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->updateApplicationStatus($_SESSION['user_id']);
?>
