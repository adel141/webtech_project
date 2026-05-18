<?php
include "../../controllers/RecruiterController.php";

require_once "../../config/remember.php";

$controller = new RecruiterController();
$controller->updateApplicationStatus($_SESSION['user_id']);
?>
