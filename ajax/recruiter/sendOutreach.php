<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->sendOutreach($_SESSION['user_id']);
?>
