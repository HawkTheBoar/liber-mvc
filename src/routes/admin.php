<?php
require_once 'models/http/Request.php';
require_once 'models/http/Route.php';
require_once 'models/http/Router.php';
require_once 'models/http/View.php';
require_once 'models/auth/user.php';
require_once 'middleware/admin_auth.php';
$router = Router::getInstance();

$router->setBasePath('/admin');
$view = new View();
$router->get('', 'admin_auth', function($params, $next) use ($view) {
    $view->render('admin/dashboard');
});