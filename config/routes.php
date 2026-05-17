<?php
/**
 * Route Definitions — Job Seeker Role
 * Maps URL patterns to Controller methods
 */

// ========== PUBLIC / AUTH ROUTES ==========
$router->get('/', [AuthController::class, 'home']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// ========== JOB SEEKER ROUTES ==========
$router->get('/seeker/dashboard', [SeekerController::class, 'dashboard']);
$router->get('/seeker/profile', [SeekerController::class, 'profile']);
$router->post('/seeker/profile', [SeekerController::class, 'updateProfile']);
$router->post('/seeker/upload-resume', [SeekerController::class, 'uploadResume']);
$router->post('/seeker/upload-photo', [SeekerController::class, 'uploadPhoto']);
$router->get('/seeker/jobs', [SeekerController::class, 'jobs']);
$router->get('/seeker/jobs/{id}', [SeekerController::class, 'jobDetail']);
$router->post('/seeker/apply/{id}', [SeekerController::class, 'apply']);
$router->post('/seeker/withdraw/{id}', [SeekerController::class, 'withdraw']);
$router->get('/seeker/applications', [SeekerController::class, 'applications']);
$router->get('/seeker/saved-jobs', [SeekerController::class, 'savedJobs']);
$router->post('/seeker/toggle-save/{id}', [SeekerController::class, 'toggleSave']);
$router->get('/seeker/alerts', [SeekerController::class, 'alerts']);
$router->post('/seeker/alerts', [SeekerController::class, 'createAlert']);
$router->post('/seeker/alerts/delete/{id}', [SeekerController::class, 'deleteAlert']);
$router->get('/seeker/messages', [MessageController::class, 'seekerInbox']);
$router->post('/seeker/messages/reply', [MessageController::class, 'reply']);
$router->get('/seeker/outreach', [SeekerController::class, 'outreach']);
$router->post('/seeker/outreach/respond/{id}', [SeekerController::class, 'respondOutreach']);
$router->get('/seeker/complaints', [SeekerController::class, 'complaints']);
$router->post('/seeker/complaints', [SeekerController::class, 'submitComplaint']);
$router->get('/seeker/notifications', [SeekerController::class, 'notifications']);

// ========== API ROUTES (AJAX) ==========
$router->get('/api/filter-jobs', [ApiController::class, 'filterJobs']);
$router->get('/api/notifications', [ApiController::class, 'getNotifications']);
