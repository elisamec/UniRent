<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';
use Classes\View\VUser; 

class CUser{
    
    public static function home(){
        $view = new VUser();
        $view->home();
    }

    public static function login(){
        $view = new VUser();
        $view->login();
    }
}