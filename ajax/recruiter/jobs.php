<?php
include "../../controllers/RecruiterController.php";

require_once "../../config/remember.php";

$controller = new RecruiterController();
$controller->getJobs($_SESSION['user_id']);
?>
