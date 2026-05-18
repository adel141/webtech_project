<?php
include "../../controllers/RecruiterController.php";

require_once "../../config/remember.php";

$controller = new RecruiterController();
$controller->deleteClient($_GET['client_id'], $_SESSION['user_id']);
?>
