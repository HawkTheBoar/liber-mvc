<?php
require_once 'models/http/Router.php';
require_once 'models/auth/user.php';
require_once 'utils/helpers.php';
start_session_if_not_started();
function admin_auth($params, $next){
    $user = User::GetUserFromSession();
    if($user && $user->IsAuthenticated() && $user->IsAdmin()){
        $next();
        return;
    }
    $router = Router::getInstance();
    $router->redirect('/auth/login', 401, new GetMessage('error', 'You are not authorized to access this page'));
}