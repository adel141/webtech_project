<?php
/**
 * Route Definitions — Employer Role
 * Maps URL patterns to Controller methods
 */

// ========== PUBLIC / AUTH ROUTES ==========
$router->get('/', [AuthController::class, 'home']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// ========== EMPLOYER ROUTES ==========
$router->get('/employer/dashboard', [EmployerController::class, 'dashboard']);
$router->get('/employer/profile', [EmployerController::class, 'profile']);
$router->post('/employer/profile', [EmployerController::class, 'updateProfile']);
$router->post('/employer/upload-logo', [EmployerController::class, 'uploadLogo']);
$router->get('/employer/jobs', [EmployerController::class, 'jobs']);
$router->get('/employer/jobs/create', [EmployerController::class, 'createJob']);
$router->post('/employer/jobs/create', [EmployerController::class, 'storeJob']);
$router->get('/employer/jobs/edit/{id}', [EmployerController::class, 'editJob']);
$router->post('/employer/jobs/edit/{id}', [EmployerController::class, 'updateJob']);
$router->post('/employer/jobs/delete/{id}', [EmployerController::class, 'deleteJob']);
$router->post('/employer/jobs/repost/{id}', [EmployerController::class, 'repostJob']);
$router->get('/employer/applicants/{id}', [EmployerController::class, 'applicants']);
$router->get('/employer/applicant-detail/{id}', [EmployerController::class, 'applicantDetail']);
$router->get('/employer/shortlisted', [EmployerController::class, 'shortlisted']);
$router->get('/employer/analytics', [EmployerController::class, 'analytics']);
$router->get('/employer/analytics/{id}', [EmployerController::class, 'jobAnalytics']);
$router->get('/employer/recruiters', [EmployerController::class, 'recruiters']);
$router->get('/employer/messages', [MessageController::class, 'employerInbox']);
$router->post('/employer/messages/send', [MessageController::class, 'send']);
$router->get('/employer/complaints', [EmployerController::class, 'complaints']);
$router->post('/employer/complaints', [EmployerController::class, 'submitComplaint']);

// ========== API ROUTES (AJAX) ==========
$router->post('/api/toggle-job-status', [ApiController::class, 'toggleJobStatus']);
$router->post('/api/update-application-status', [ApiController::class, 'updateApplicationStatus']);
$router->get('/api/notifications', [ApiController::class, 'getNotifications']);
