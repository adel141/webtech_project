<?php
include "../../controllers/RecruiterController.php";

require_once "../../config/remember.php";

$controller = new RecruiterController();
$controller->deleteJob($_GET['job_id'], $_SESSION['user_id']);
?>
