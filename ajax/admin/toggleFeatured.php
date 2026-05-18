<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->toggleFeatured($_GET['job_id'], $_GET['featured']);
?>
