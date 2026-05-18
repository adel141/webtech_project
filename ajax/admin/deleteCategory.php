<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->deleteCategory($_GET['category_id']);
?>
