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
    public static function getAllTables(){
        $schema = loadJson('db_schema.json');
        $keys = array_keys($schema);
        return array_map(function($key){
            return new Table($key);
        }, $keys);
    }
    public function getFieldNames(){
        return array_map(function($field){
            return $field->name;
        }, $this->fields);
    }
    public function fetchFields(){
        $pdo = pdoconnect::getInstance();
        $sql = "SELECT " . implode(", ", array_merge($this->getFieldNames(), ["id", "is_deleted"])) . " FROM $this->name ORDER BY is_deleted ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
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
                    $this->fields[] = new PasswordField($field['name'], $field['type'], $field['hash'], $field['required'], $field['unique']);
                    break;
                case 'enum':
                    $this->fields[] = new EnumField($field['name'], $field['type'], $field['required'], $field['unique'], $field['values']);
                    break;
                case 'relation':
                    $this->fields[] = new RelationField($field['name'], $field['type'], $field['required'], $field['unique'], $field['relation']['table'], $field['relation']['field']);
                    break;
                case 'boolean':
                    $this->fields[] = new BooleanField($field['name'], $field['type'], $field['required'], $field['unique']);
                    break;
                default:
                    $this->fields[] = new Field($field['name'], $field['type'], $field['required'], $field['unique']);
            }
        }

    }
}