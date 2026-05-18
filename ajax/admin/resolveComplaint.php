<?php
include "../../controllers/AdminController.php";

$controller = new AdminController();
$controller->resolveComplaint($_GET['complaint_id']);
?>
