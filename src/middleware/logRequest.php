<?php
function logRequest(array $params, callable $next) {
    // Log the request (e.g., to a file or console)
    error_log("Request logged: " . json_encode($params));

    $next(); // Continue to the next middleware/handler
}