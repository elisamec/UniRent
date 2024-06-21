<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';
use Classes\View\VTest; 

class CTest{
    public static function test(){
        echo "sono in CTest";
        $view = new VTest();
        $view->home();
    }
}

