<?php
include "../../controllers/AdminController.php";
$controller = new AdminController();
$categories = $controller->getAllCategories();
?>