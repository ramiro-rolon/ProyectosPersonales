<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        if (isset($this->routes[$method][$uri])) {
            $callback = $this->routes[$method][$uri];
            
            if (is_array($callback)) {
                $controller = new $callback[0]();
                $action = $callback[1];
                $controller->$action();
            } else {
                call_user_func($callback);
            }
        } else {
            foreach ($this->routes[$method] ?? [] as $route => $callback) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route);
                $pattern = "#^$pattern$#";
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    
                    if (is_array($callback)) {
                        $controller = new $callback[0]();
                        $action = $callback[1];
                        $controller->$action(...$matches);
                    } else {
                        call_user_func($callback, ...$matches);
                    }
                    return;
                }
            }
            
            http_response_code(404);
            echo "404 - Página no encontrada";
        }
    }
}
