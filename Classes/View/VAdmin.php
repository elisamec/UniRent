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

    public function home(array $stats, array $banned, array $requests, array $reports, int $countRequests, int $countReports){
        $this->smarty->assign('stats', $stats);
        $this->smarty->assign('banned', $banned);
        $this->smarty->assign('requests', $requests);
        $this->smarty->assign('reports', $reports);
        $this->smarty->assign('countRequests', $countRequests);
        $this->smarty->assign('countReports', $countReports);
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
    public function profile(EStudent | EOwner $user, string $userType, array $reviewsData, array $requests, array $reports, int $countRequests, int $countReports){
        $this->smarty->assign('user', $user);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('userType', $userType);
        $this->smarty->assign('requests', $requests);
        $this->smarty->assign('reports', $reports);
        $this->smarty->assign('countRequests', $countRequests);
        $this->smarty->assign('countReports', $countReports);
        $this->smarty->display('Admin/profile.tpl');
    }
    public function readMoreSupportRequest(array $requests, int $count, array $reports, int $countReports, int $countRequests){ 
        $this->smarty->assign('requests', json_encode($requests));
        $this->smarty->assign('count', $count);
        $this->smarty->assign('reports', $reports);
        $this->smarty->assign('countRequests', $countRequests);
        $this->smarty->assign('countReports', $countReports);
        $this->smarty->display('Admin/supportRequests.tpl');
    }
}