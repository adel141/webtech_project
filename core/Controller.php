<?php
/**
 * Base Controller — View rendering, redirects, JSON responses
 */
class Controller {

    /**
     * Render a view file with layout
     */
    protected function view($name, $data = [], $layout = true) {
        extract($data);
        $viewFile = BASE_PATH . '/views/' . $name . '.php';
        
        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo "View not found: $name";
            return;
        }

        if ($layout) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            require BASE_PATH . '/views/layouts/header.php';
            echo $content;
            require BASE_PATH . '/views/layouts/footer.php';
        } else {
            require $viewFile;
        }
    }

    /**
     * Redirect to URL
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Return JSON response (for AJAX)
     */
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            http_response_code(403);
            die('Invalid CSRF token');
        }
    }

    /**
     * Get POST data with default
     */
    protected function input($key, $default = '') {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    /**
     * Get GET data with default
     */
    protected function query($key, $default = '') {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }

    /**
     * Flash a session message
     */
    protected function flash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Get and clear flash message
     */
    protected function getFlash() {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
