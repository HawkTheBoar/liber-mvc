<?php
function getFromPostOrThrowError($key){
    if(!isset($_POST[$key])){
        throw new Exception("Missing $key in POST request");
    }
    return $_POST[$key];
}
function sanitizeUserInput($input) {
    if (is_array($input)) {
        // Recursively sanitize array inputs
        return array_map(function ($value) {
            return sanitizeUserInput($value);
        }, $input);
    }

    if (is_string($input)) {
        // Trim unnecessary whitespace
        $input = trim($input);

        // Convert special characters to HTML entities to prevent XSS
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        // If a PDO connection is provided, use prepared statements for SQL safety
    }

    return $input;
}

function loadJson(string $path){
    $config = file_get_contents($path);
    $config = json_decode($config, true);
    return $config;
}
function start_session_if_not_started(){
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
}