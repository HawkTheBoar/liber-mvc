<?php
require_once 'models/http/Router.php';
function delete_controller($params, $next) {
    $router = Router::getInstance();
    $table = $params['table'];
    $id = $params['id'];
    $pdo = pdoconnect::getInstance();

    $query = "UPDATE $table SET is_deleted = true WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    // get the number of affected rows
    $affected_rows = $stmt->rowCount();
    if($affected_rows > 0){
        $router->redirect('/admin', 200, new GetMessage('success', 'Record deleted successfully'));
    }else{
        $router->redirect('/admin', 200, new GetMessage('error', 'Record not found'));
    }
}