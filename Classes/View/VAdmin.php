<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;
use Classes\Entity\EStudent;
use Classes\Entity\EOwner;

class VAdmin
{
    private $smarty;

    public function __construct(){

        $this->smarty = StartSmarty::configuration();
    }

    public function home(array $stats, array $banned){
        $this->smarty->assign('stats', $stats);
        $this->smarty->assign('banned', $banned);
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
    public function profile(EStudent | EOwner $user, string $userType, array $reviewsData) {
        $this->smarty->assign('user', $user);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('userType', $userType);
        $this->smarty->display('Admin/profile.tpl');
    }
}