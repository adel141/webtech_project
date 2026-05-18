<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->pipeline($_SESSION['user_id']);
?>
