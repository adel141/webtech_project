<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->updateProfile($_SESSION['user_id']);
?>
