<?php
class Route {
    private string $method;
    private string $path;
    private array $handlers;

    public function __construct(string $method, string $path, array $handlers) {
        $this->method = $method;
        $this->path = $path;
        $this->handlers = $handlers;
    }

    public function matches(string $method, string $uri): bool {
        if ($this->method !== $method) {
            return false;
        }

        // Simple pattern matching (support dynamic segments like /user/{id})
        $pattern = preg_replace('/\{[^\/]+\}/', '([^\/]+)', $this->path);

        // Make trailing slash optional
        $pattern = rtrim($pattern, '/') . '(/)?'; // Add optional slash
        $pattern = "#^{$pattern}$#";
 
        return preg_match($pattern, $uri);
    }

    public function getHandlers(): array {
        return $this->handlers;
    }

    public function extractParameters(string $uri): array {
        // Match dynamic segments in the path pattern (e.g., {id}, {name})
        preg_match_all('/\{([^\/]+)\}/', $this->path, $parameterNames);
    
        // Replace dynamic segments with regular expression capture groups
        $pattern = preg_replace('/\{[^\/]+\}/', '([^\/]+)', $this->path);
        $pattern = "#^{$pattern}$#"; // Add start and end anchors
    
        // Check if the URI matches the pattern
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove the full match
            
            // Create an associative array to store parameters by their name
            $params = [];
            foreach ($parameterNames[1] as $index => $paramName) {
                $params[$paramName] = $matches[$index];
            }
    
            return $params;
        }
    
        return [];
    }    
}
