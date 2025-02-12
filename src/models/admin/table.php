<?php
require_once 'models/admin/field.php';
require_once 'utils/helpers.php';

class Table{
    public $name;
    public $fields;
    public function __construct($name){
        $this->name = $name;
        $this->fields = [];
        $this->setFields();
    }
    public function setFields(){
        $schema = loadJson('db_schema.json');
        $fields = $schema[$this->name]['fields'];
        $skip_fields = $schema[$this->name]['form_skip_fields'];
        foreach($fields as $field){
            if(in_array($field['name'], $skip_fields)){
                continue;
            }
            switch($field['type']){
                case 'password':
                    $this->fields[] = new PasswordField($field['name'], $field['type'], $field['hash'], $field['required']);
                    break;
                case 'enum':
                    $this->fields[] = new EnumField($field['name'], $field['type'], $field['required'], $field['values']);
                    break;
                case 'relation':
                    $this->fields[] = new RelationField($field['name'], $field['type'], $field['required'], $field['relation']['table'], $field['relation']['field']);
                    break;
                case 'boolean':
                    $this->fields[] = new BooleanField($field['name'], $field['type'], $field['required']);
                    break;
                default:
                    $this->fields[] = new Field($field['name'], $field['type'], $field['required']);
            }
        }

    }
}