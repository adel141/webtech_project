<?php
/**
 * Front Controller — Entry point for all requests
 * Employer Module
 */

// Load configuration
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

// Load core classes
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Middleware.php';

// Load models
require_once BASE_PATH . '/models/UserModel.php';
require_once BASE_PATH . '/models/EmployerModel.php';
require_once BASE_PATH . '/models/SeekerModel.php';
require_once BASE_PATH . '/models/JobModel.php';
require_once BASE_PATH . '/models/ApplicationModel.php';
require_once BASE_PATH . '/models/CategoryModel.php';
require_once BASE_PATH . '/models/MessageModel.php';
require_once BASE_PATH . '/models/ComplaintModel.php';
require_once BASE_PATH . '/models/RecruiterModel.php';
require_once BASE_PATH . '/models/AnalyticsModel.php';
require_once BASE_PATH . '/models/AlertModel.php';

// Load controllers
require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/EmployerController.php';
require_once BASE_PATH . '/controllers/SeekerController.php';
require_once BASE_PATH . '/controllers/MessageController.php';
require_once BASE_PATH . '/controllers/ApiController.php';

// Start session
Auth::init();

// Initialize router
$router = new Router();

// Load route definitions
require_once BASE_PATH . '/config/routes.php';

// Dispatch the request
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$router->dispatch($method, $uri);
