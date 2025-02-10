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
        $pattern = "#^{$pattern}$#";

        return preg_match($pattern, $uri);
    }

    public function getHandlers(): array {
        return $this->handlers;
    }

    public function extractParameters(string $uri): array {
        $pattern = preg_replace('/\{[^\/]+\}/', '([^\/]+)', $this->path);
        $pattern = "#^{$pattern}$#";

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove the full match
            return $matches;
        }

        return [];
    }
}
