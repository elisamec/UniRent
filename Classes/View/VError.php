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

    public function error(int $errorCode, ?string $username=null, string $requestSuccess='null', string $banReason='null'){
        $this->smarty->assign('error', $errorCode);
        $this->smarty->assign('username', $username);
        $this->smarty->assign('requestSuccess', $requestSuccess);
        $this->smarty->assign('banReason', $banReason);
        $this->smarty->display('Error/error.tpl');
    }
}