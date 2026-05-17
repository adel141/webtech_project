<?php
include "../../controllers/AdminController.php";
$controller = new AdminController();
$controller->toggleUserStatus($_POST['user_id'], $_POST['status']);
?>