<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->getUserByRole($_GET['role']);
?>
