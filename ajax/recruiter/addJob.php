<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->addJob($_SESSION['user_id']);
?>
