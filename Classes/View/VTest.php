<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VTest{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(){

        $this->smarty->display('login.tpl');
    }
}