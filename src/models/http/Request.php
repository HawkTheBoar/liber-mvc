<?php
class Request {
    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri(): string {
        $uri = $_SERVER['REQUEST_URI'];
        return strtok($uri, '?'); // Remove query string
    }
    
}
