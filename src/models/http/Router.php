<?php
class Router {
    private array $routes = [];
    private static $router;
    private $base_path = '';
    public static function getInstance(){
        if(self::$router == null){
            self::$router = new Router();
        }
        self::$router->base_path = '';
        return self::$router;
    }
    public function redirect(string $path, int $statusCode, ...$messages){
        header("Location: $path", true, $statusCode);
        exit();
    }
    public function redirectByName(string $routeName, int $statusCode){

    }
    public function setBasePath($base_path){
        self::$router->base_path = $base_path;
    }
    public function addRoute(string $method, string $path, array $handlers): void {
        self::$router->routes[] = new Route($method, self::$router->base_path . $path, $handlers);
    }

    public function get(string $path, ...$handlers): void {
        self::$router->addRoute('GET', $path, $handlers);
    }

    public function post(string $path, ...$handlers): void {
        self::$router->addRoute('POST', $path, $handlers);
    }

    public function dispatch(Request $request): void {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach (self::$router->routes as $route) {
            if ($route->matches($method, $uri)) {
                $parameters = $route->extractParameters($uri);
                self::$router->handleMiddleware($route->getHandlers(), $parameters);
                return;
            }
        }

        // If no route matches, return a 404 response
        http_response_code(404);
        echo "404 Not Found";
    }

    private function handleMiddleware(array $handlers, array $parameters): void {
        self::sanitizeParameters($parameters);
        $next = function() use (&$handlers, $parameters, &$next) {
            if (!empty($handlers)) {
                $handler = array_shift($handlers);
                // $handler($parameters, $next);
                call_user_func($handler, $parameters, $next);
            }
        };

        $next(); // Start middleware chain
    }
    static function sanitizeParameters(array $params): array {
        $sanitized = [];
    
        foreach ($params as $param) {
            // Check for SQL injection patterns and remove them
            $param = preg_replace('/(SELECT|INSERT|DELETE|UPDATE|DROP|TRUNCATE|ALTER|CREATE|EXEC|UNION|--|#|\/\*|\*\/|;)/i', '', $param);
    
            // Trim spaces and special characters
            $param = trim($param);
    
            // Apply htmlspecialchars to prevent XSS
            $param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
    
            // Add to sanitized array
            $sanitized[] = $param;
        }
    
        return $sanitized;
    }
}
