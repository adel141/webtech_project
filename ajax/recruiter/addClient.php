<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->addClient($_SESSION['user_id']);
?>
