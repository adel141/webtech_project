<?php
/**
 * Application Configuration
 */

// Base paths
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/JobPortal/public');

// Upload settings
define('UPLOAD_PATH', BASE_PATH . '/public/uploads');
define('MAX_RESUME_SIZE', 5 * 1024 * 1024); // 5 MB
define('MAX_IMAGE_SIZE', 2 * 1024 * 1024);  // 2 MB
define('ALLOWED_RESUME_TYPES', ['application/pdf']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// App info
define('APP_NAME', 'JobPortal');
define('APP_VERSION', '1.0.0');
