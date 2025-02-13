<?php
require_once 'models/http/Request.php';
require_once 'models/http/Route.php';
require_once 'models/http/Router.php';
require_once 'models/http/View.php';
require_once 'models/auth/user.php';
require_once 'middleware/admin_auth.php';
require_once 'middleware/check_table_exists.php';
require_once 'models/pdoconnect.php';
require_once 'utils/helpers.php';
require_once 'controllers/admin/delete_controller.php';
require_once 'controllers/admin/add/add_users_controller.php';
require_once 'controllers/admin/add/add_general_controller.php';
require_once 'models/admin/table.php';
require_once 'models/admin/field.php';

$router = Router::getInstance();
$db_schema = loadJson('db_schema.json');

$router->setBasePath('/admin');
$view = new View();


$router->get('/', 'admin_auth', function($params, $next) use ($view) {
    $view->render('admin/dashboard');
});

$router->get('/delete/{table}/{id}', 'check_table_exists', 'admin_auth', 'delete_controller');

$router->get('/add/{table}', 'check_table_exists', 'admin_auth', function($params, $next) use ($view) {
    $table_name = $params['table'];
    $table = new Table($table_name);
    $view->render('admin/add', ['fields' => $table->fields]);
});
$router->post('/add/{table}', 'check_table_exists' ,'admin_auth', 'add_general_controller');
$router->post('/add/users', 'admin_auth', 'add_users_controller');