<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;

class VAdmin
{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(array $stats){
        $this->smarty->assign('stats', $stats);
        $this->smarty->display('Admin/dashboard.tpl');
    }

    public function login(){
        $this->smarty->assign('usernameError', false);
        $this->smarty->assign('passwordError', false);
        $this->smarty->display('Admin/login.tpl');
    }
    public function loginError(bool $usernameError, bool $passwordError){
        $this->smarty->assign('usernameError', $usernameError);
        $this->smarty->assign('passwordError', $passwordError);
        $this->smarty->display('Admin/login.tpl');
    }
}