<?php 
include "../../controllers/AdminController.php";
$controller = new AdminController();
$employers = $controller->getUserByRole($_POST['role']);
?>