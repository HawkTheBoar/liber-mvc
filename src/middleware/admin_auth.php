<?php
require_once 'models/http/Router.php';
function admin_auth($params, $next){
    $router = Router::getInstance();
    $router->redirect('/auth/login', 401, new GetMessage('error', 'You are not authorized to access this page'));
}