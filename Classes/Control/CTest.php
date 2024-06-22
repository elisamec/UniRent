<?php

namespace Classes\Control;
require __DIR__.'/../../vendor/autoload.php';
use Classes\View\VTest; 

class CTest{
    public function test(){
        $view = new VTest();
        $view->home();
    }
}

