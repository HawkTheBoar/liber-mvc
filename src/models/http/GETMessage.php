<?php

class GetMessage{
    private $content;
    private $name;

    public function __construct($name, $content){
        $this->name = $name;
        $this->content = $content;
    }
    public function __toString()
    {
        return str_replace(' ', '+', $this->name . '=' . $this->content);
    }
    public static function formGetParams(...$messages){
        $messages = $messages[0];
        $messages[0]->name = '?' . $messages[0]->name;
        return implode('&', $messages);
    }
}