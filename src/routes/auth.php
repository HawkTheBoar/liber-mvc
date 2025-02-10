<?php
require_once 'models/http/Request.php';
require_once 'models/http/Route.php';
require_once 'models/http/Router.php';
require_once 'models/http/View.php';
$router = Router::getInstance();
$view = new View();
$router->setBasePath('/auth');
$router->get('/login', function($params, $next) use ($view) {
    $view->render('auth/login');
});

$router->get('/register', function($params, $next) use ($view) {
    $view->render('auth/register');
});
$router->get('/logout', function($params, $next) use ($view) {
    $view->render('auth/logout');
});