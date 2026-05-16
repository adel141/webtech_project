<?php
include "../../controllers/AdminController.php";
$controller = new AdminController();
// $controller->dashboard();
$controller->approverUser($_POST['user_id']);
?>