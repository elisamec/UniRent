<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VError
{
    /**
     * @var Smarty $smarty
     */
    private $smarty;

    /**
     * __construct
     * 
     * This method is used to initialize the smarty object
     */
    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    /**
     * error
     * 
     * This method is used to show the error page
     * @param int $errorCode
     * @param ?string $username
     * @param string $requestSuccess
     * @param string $banReason
     */
    public function error(int $errorCode, ?string $username=null, string $requestSuccess='null', string $banReason='null'){
        $this->smarty->assign('error', $errorCode);
        $this->smarty->assign('username', $username);
        $this->smarty->assign('requestSuccess', $requestSuccess);
        $this->smarty->assign('banReason', $banReason);
        $this->smarty->display('Error/error.tpl');
    }
}