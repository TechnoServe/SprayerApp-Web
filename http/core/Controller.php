<?php

namespace sprint\http\core;

use \sprint\sview\SView;

class Controller 
{    
    public $viewsPath = "";

    public function model($model) 
    {
        if (file_exists('app/models/' . $model . '.php')) 
        {
            $className = "\\sprint\\app\\models\\{$model}";
            return new $className;
        }
    }
    
    public function view(String $view, array $data = [], String $type = "html")
    {
        echo $this->cView($view, $data, $type);
    }

    public function cView(String $view, array $data = [], String $type = "html") 
    {
        ob_start();
        
        $file = $this->viewsPath . $view . '.php';

        if (file_exists($file)) 
        {
            SView::view($file, $data);
        }else
        {
            throw new \Exception("Views not found");
        }
        
        return ob_get_clean();
    }
}
