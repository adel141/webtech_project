<?php
/**
 * Middleware — RBAC guards, CSRF helpers
 */
class Middleware {

    /**
     * Require authentication — redirect to login if not logged in
     */
    public static function requireAuth() {
        if (!Auth::check()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in to continue.'];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Require a specific role — 403 if wrong role
     */
    public static function requireRole($role) {
        self::requireAuth();
        if (Auth::role() !== $role) {
            http_response_code(403);
            require BASE_PATH . '/views/errors/403.php';
            exit;
        }
    }

    /**
     * Require account verification (for employer/recruiter)
     */
    public static function requireVerified() {
        self::requireAuth();
        if (!Auth::isVerified() && in_array(Auth::role(), ['employer', 'recruiter'])) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Your account is pending verification by admin.'];
            header('Location: ' . BASE_URL . '/' . Auth::role() . '/dashboard');
            exit;
        }
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrf() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Render CSRF hidden input field
     */
    public static function csrfField() {
        $token = self::generateCsrf();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Verify CSRF token from POST
     */
    public static function verifyCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die('Invalid CSRF token.');
        }
    }
}
