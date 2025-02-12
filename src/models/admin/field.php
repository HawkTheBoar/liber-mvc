<?php

class Field{
    public $name;
    public $type;
    public $required;
    public $default_value;
    public function __construct($name, $type, $required, $default_value = null){
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default_value = $default_value;
    }
    public function render(){
        echo "<input type='$this->type' name='$this->name' required='$this->required' value='$this->default_value'>";
    }
}
class NoRenderField extends Field{
    public function render(){
        return;
    }
}
class HiddenField extends Field{
    public $value;
    public function __construct($name, $type, $required, $value){
        parent::__construct($name, $type, $required, $value);
    }
    public function render(){
        echo "<input type='hidden' value='$this->value' name='$this->name' required='$this->required' value='$this->default_value'>";
    }
}
class AutoField extends Field{
    public function render(){
        echo "<input type='text' name='$this->name' required='$this->required' readonly>";
    }
}
class PasswordField extends Field{
    public $hash;
    public function __construct($name, $type, $hash, $required){
        parent::__construct($name, $type, $required);
        $this->hash = $hash;
    }
    public function render(){
        echo "<input type='password' name='$this->name' required='$this->required'>";
    }
}
class EnumField extends Field{
    public $options;
    public function __construct($name, $type, $required, $options){
        parent::__construct($name, $type, $required);
        $this->options = $options;
    }
    public function render(){
        $html = "<select name='$this->name' required='$this->required'>";
        foreach($this->options as $option){
            $html .= "<option value='$option'>$option</option>";
        }
        $html .= "</select>";
        echo $html;
    }
}
class RelationField extends Field{
    public $table;
    public $field;
    public function __construct($name, $type, $required, $table, $field){
        parent::__construct($name, $type, $required);
        $this->table = $table;
        $this->field = $field;
    }
    public function render(){
        $pdo = pdoconnect::getInstance();
        $query = "SELECT * FROM $this->table";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $html = "<select name='$this->name' required='$this->required'>";
        foreach($rows as $row){
            $relation_field = $row[$this->field];
            $name = $row['name'];
            $html .= "<option value='$relation_field'>$name</option>";
        }
        $html .= "</select>";
        echo $html;
    }
}
class BooleanField extends Field{
    public function render(){
        $html = "<input type='checkbox' name='$this->name' required='$this->required'></input>";
        echo $html;
    }
}