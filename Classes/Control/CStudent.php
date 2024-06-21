<?php


namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';
use Classes\View\VStudent; 

class CStudent{
    public static function home(){
        $view = new VStudent();
        $view->home();
    }
}