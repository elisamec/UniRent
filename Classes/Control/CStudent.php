<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';
use Classes\View\VStudent; 

/**
 * Student controller class
 *
 * This class is responsible for handling student-related requests
 * 
 * @package Classes\Control
 *
 */
class CStudent{

    /**
     * Display the home page
     * 
     */
    public static function home(){
        $view = new VStudent();
        $view->home();
    }
}