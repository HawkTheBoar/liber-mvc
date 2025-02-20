<?php
include_once 'models/http/Router.php';
require_once 'models/catalog/category.php';
$router = Router::getInstance();
$router->setBasePath('/catalog');

$router->get('/{id}', function($params, $next) use ($router) {
    echo "Welcome to the catalog!";
    $category = Category::findCategoryByID($params['id']);
    if($category === null){
        $router->notFound();
    }
});