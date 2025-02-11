<?php
require_once 'models/http/Request.php';
require_once 'models/http/Route.php';
require_once 'models/http/Router.php';
require_once 'models/http/View.php';
require_once 'models/auth/user.php';
require_once 'middleware/admin_auth.php';
require_once 'models/pdoconnect.php';
require_once 'utils/helpers.php';
$router = Router::getInstance();
$db_schema = loadJson('db_schema.json');

$router->setBasePath('/admin');
$view = new View();


$router->get('/', 'admin_auth', function($params, $next) use ($view) {
    $view->render('admin/dashboard');
});
$router->get('/delete/{table}/{id}', 'admin_auth', function($params, $next) use($router) {
    $table = $params['table'];
    $id = $params['id'];
    $pdo = pdoconnect::getInstance();

    $query = "DELETE FROM $table WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    // get the number of affected rows
    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0){
        $router->redirect('/admin', 200, new GetMessage('success', 'Record deleted successfully'));
    }else{
        $router->redirect('/admin', 200, new GetMessage('error', 'Record not found'));
    }
});
$router->get('/add/{table}', 'admin_auth', function($params, $next) use ($view, $db_schema) {
    $table = $params['table'];
    var_dump($db_schema[$table]['fields']);
    $view->render('admin/add', ['table' => $db_schema[$table], 'fields' => $db_schema[$table]['fields']]);
});