<?php
/**
 * Router — Maps URLs to controller methods
 */
class Router {
    private $routes = ['GET' => [], 'POST' => []];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch($method, $uri) {
        // Remove query string
        $uri = strtok($uri, '?');
        // Remove base URL prefix
        $base = str_replace('/index.php', '', BASE_URL);
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }
        if (strpos($uri, '/index.php') === 0) {
            $uri = substr($uri, strlen('/index.php'));
        }
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') $uri = '/';

        $routes = $this->routes[$method] ?? [];

        // Exact match first
        if (isset($routes[$uri])) {
            return $this->call($routes[$uri]);
        }

        // Pattern match with parameters
        foreach ($routes as $route => $callback) {
            $pattern = preg_replace('#\{(\w+)\}#', '(\w+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->call($callback, $matches);
            }
        }

        // 404
        http_response_code(404);
        require BASE_PATH . '/views/errors/404.php';
        exit;
    }

    private function call($callback, $params = []) {
        if (is_array($callback)) {
            $controller = new $callback[0]();
            return call_user_func_array([$controller, $callback[1]], $params);
        }
        return call_user_func_array($callback, $params);
    }
}
