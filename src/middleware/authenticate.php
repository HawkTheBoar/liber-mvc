<?php

function authenticate(array $params, callable $next) {
    // Simulate authentication check
    $isAuthenticated = true; // Replace with real logic

    if (!$isAuthenticated) {
        http_response_code(401);
        echo "Unauthorized";
        return;
    }

    $next(); // Continue to the next middleware/handler
}