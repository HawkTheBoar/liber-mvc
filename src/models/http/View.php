<?php
class View{
    private string $viewPath;

    public function __construct(string $viewPath = ''){
        if($viewPath === ''){
            $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/views';
        }
        $this->viewPath = $viewPath;
    }

    public function render($view, $params = []){
        $view = $this->viewPath . '/' . $view . '.php';
        if(!file_exists($view)){
            http_response_code(404);
            echo "View does not exist";
            return;
        }
        extract($params);
        include_once 'views/components/head.php';
        include $view;
    }
}