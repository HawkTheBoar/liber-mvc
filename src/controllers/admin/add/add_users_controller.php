<?php
require_once 'models/admin/table.php';
require_once 'models/auth/user.php';
require_once 'utils/helpers.php';
require_once 'models/http/Router.php';

function add_users_controller(){
    $db_schema = loadJson('db_schema.json');
    $router = Router::getInstance();
    $table = new Table('users');
    $fields = $table->fields;
    $post_values = [];
    $form_skip_fields = $db_schema['users']['form_skip_fields'];
    foreach($fields as $field){
        // check if form field is a form_skip_field
        if(in_array($field->name, $form_skip_fields)){
            continue;
        }
        try{
            $post_values[$field->name] = getFromPostOrThrowError($field->name);

        } catch(Exception $e){
            $router->redirect('/admin/add/users', 400, new GetMessage('error', 'Missing required field'));
            return;
        }
    }
    switch($post_values['role']){
        case 'admin':
            try{
                UserFactory::CreateAdmin($post_values['email'], $post_values['password'], $post_values['username']);

            } catch(Exception $e){
                $router->redirect('/admin/add/users', 400, new GetMessage('error', $e->getMessage()));
                return;
            }
            break;
        case 'user':
            try{
                UserFactory::CreateUser($post_values['email'], $post_values['password'], $post_values['username']);
            } catch(Exception $e){
                $router->redirect('/admin/add/users', 400, new GetMessage('error',  $e->getMessage()));
                return;
            }
            break;
    }
    $router->redirect('/admin', 200, new GetMessage('success', 'User added successfully'));
}