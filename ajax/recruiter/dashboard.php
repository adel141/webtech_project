<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->dashboard($_SESSION['user_id']);
?>
