<?php
require_once 'models/http/Router.php';
function login_controller($params, $next) {
    $router = Router::getInstance();
    try{
        $email = getFromPostOrThrowError('email');
        $password = getFromPostOrThrowError('password');
    } catch(Exception $e){
        $router->redirect(
            '/auth/login',
            200,
            new GetMessage('error', $e->getMessage())
        );
    }
    $user = new User($email);
    $res = $user->Authenticate($password);
    if($res) {
        $user->Login();
        $router->redirect('/admin', 200, new GetMessage('success', 'You have been logged in'));
    } else {
        $router->redirect('/auth/login', 200, new GetMessage('error', 'Invalid credentials'));
    }
}