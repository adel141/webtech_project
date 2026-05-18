<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->deleteJob($_GET['job_id']);
?>
