<?php
require_once 'models/http/View.php';
function dashboard_controller($params, $next){
    

    // render view
    $view = new View();
    $view->render('admin/dashboard');
}