<?php
// include
require_once 'models/auth/user.php';
session_start();
require 'views/components/navbar.php';
require_once 'models/http/Request.php';
require_once 'models/http/Route.php';
require_once 'models/http/Router.php';
require_once 'middleware/authenticate.php';
require_once 'middleware/logRequest.php';
require_once 'models/http/GETMessage.php';
// Require other Routes
require 'routes/auth.php';
require 'routes/admin.php';
// UserFactory::CreateAdmin('admin', 'admin@gmail.com', 'admin');
// Create a new Router instance
$router = Router::getInstance();

// Define routes with middleware
$router->get('/', function($params, $next) {
    echo "Welcome to the home page!";
});

$router->get('/log', 'logRequest', function($params, $next) {
    echo "Logging this request!";
});


// Dispatch the request
$request = new Request();
$router->dispatch($request);
