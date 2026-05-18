<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->rejectUser($_GET['user_id']);
?>
