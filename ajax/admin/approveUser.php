<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->approveUser($_GET['user_id']);
?>
