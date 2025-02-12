<?php
function check_table_exists($params, $next){
    $table = $params['table'];
    $db_schema = loadJson('db_schema.json');
    if(array_key_exists($table, $db_schema)){
        $next();
        return;
    }
    $router = Router::getInstance();
    $router->redirect('/admin', 404, new GetMessage('error', 'Table not found'));
}