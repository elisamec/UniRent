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

    public function home(array $stats, array $banned, ?string $modalMessage){
        $this->smarty->assign('stats', $stats);
        $this->smarty->assign('banned', $banned);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/dashboard.tpl');
    }

    public function login(){
        $this->smarty->assign('passwordError', false);
        $this->smarty->display('Admin/login.tpl');
    }
    public function loginError(){
        $this->smarty->assign('passwordError', true);
        $this->smarty->display('Admin/login.tpl');
    }
    public function profile(EStudent | EOwner $user, string $userType, array $reviewsData, ?int $reportId, ?string $modalMessage){
        $this->smarty->assign('user', $user);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('userType', $userType);
        $this->smarty->assign('reportId', $reportId);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/profile.tpl');
    }
    public function readMoreSupportRequest(array $requests, int $count, ?string $modalMessage){ 
        $this->smarty->assign('requests', json_encode($requests));
        $this->smarty->assign('count', $count);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/supportRequests.tpl');
    }
    public function readMoreReports(array $reports, int $count, ?string $modalMessage){
        $this->smarty->assign('reports', json_encode($reports));
        $this->smarty->assign('count', $count);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/reports.tpl');
    }
}