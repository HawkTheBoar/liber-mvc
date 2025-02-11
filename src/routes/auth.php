<?php
require_once 'models/http/Request.php';
require_once 'models/http/Route.php';
require_once 'models/http/Router.php';
require_once 'models/http/View.php';
require_once 'models/auth/user.php';
require_once 'controllers/auth/login_controller.php';

$router = Router::getInstance();
$view = new View();
$router->setBasePath('/auth');
$router->get('/login', function($params, $next) use ($view) {
    $success = $_GET['success'] ?? null;
    $error = $_GET['error'] ?? null;
    $view->render('auth/login', ['success' => $success, 'error' => $error]);
});
$router->post('/login', 'login_controller');
$router->get('/register', function($params, $next) use ($view) {
    $view->render('auth/register');
});
$router->get('/logout', function($params, $next) use ($router) {
    User::Logout();
    $router->redirect('/auth/login', 200, new GetMessage('success', 'You have been logged out'));
});