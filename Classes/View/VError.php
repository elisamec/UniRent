<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VError
{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function error(int $errorCode){
        $this->smarty->assign('error', $errorCode);
        $this->smarty->display('Error/error.tpl');
    }
}