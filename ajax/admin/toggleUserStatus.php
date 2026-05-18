<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->toggleUserStatus($_GET['user_id'], $_GET['status']);
?>
