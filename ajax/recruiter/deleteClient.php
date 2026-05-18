<?php
include "../../controllers/RecruiterController.php";

session_start();

$controller = new RecruiterController();
$controller->deleteClient($_GET['client_id'], $_SESSION['user_id']);
?>
