<?php
require_once 'models/http/Router.php';
require_once 'models/admin/table.php';
require_once 'models/http/GETMessage.php';
require_once 'models/pdoconnect.php';
function add_general_controller($params, $next) {
    $router = Router::getInstance();
    $table_name = $params['table'];
    $table = new Table($table_name);
    $fields = $table->fields;
    $data = [];
    foreach($fields as $field){
        try{
            $postVal = getFromPostOrThrowError($field->name);
            if($postVal === 'NULL'){
                $postVal = null;
            }
            if($field->type === 'password' && $field->hash){
                $postVal = password_hash($postVal, PASSWORD_DEFAULT);
            }
            if($field->unique){
                $pdo = pdoconnect::getInstance();
                $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE " . $field->name . " = ? AND is_deleted = false");
                $stmt->execute([$postVal]);
                $result = $stmt->fetch();
                if($result){
                    $router->redirect('/admin/add/' . $table_name, 400, new GetMessage('error', 'Record not added. ' . $field->name . ' must be unique'));
                    return;
                }
            }
            $data[$field->name] = $postVal;
        } catch(Exception $e) {
            if($field->isRequired())
            {
                $router->redirect('/admin/add' . $table_name, 400,
                new GetMessage(
                    'error',
                    'Missing required field ' . $field->name)
                );
                return;
            }
        }
    }
    $pdo = pdoconnect::getInstance();
    $field_names = array_keys($data);
    $sql = "INSERT INTO $table_name (" . implode(',', $field_names) . ") VALUES (" . implode(',', array_fill(0, count($field_names), '?')) . ")";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($data));
    $affectedRows = $stmt->rowCount();
    if($affectedRows > 0){
        $router->redirect('/admin', 200, new GetMessage('success', 'Record added successfully'));
    }else{
        $router->redirect('/admin/add/' . $table_name, 400, new GetMessage('error', 'Record not added'));
    }
}