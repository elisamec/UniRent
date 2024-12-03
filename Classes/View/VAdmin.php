<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use StartSmarty;
use Classes\Entity\EStudent;
use Classes\Entity\EOwner;

class VAdmin
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
     * home
     * 
     * This method is used to show the dashboard
     * @param array $stats
     * @param array $banned
     * @param ?string $modalMessage
     */
    public function home(array $stats, array $banned, ?string $modalMessage){
        $this->smarty->assign('stats', $stats);
        $this->smarty->assign('banned', $banned);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/dashboard.tpl');
    }

    /**
     * login
     * 
     * This method is used to show the login page
     */
    public function login(){
        $this->smarty->assign('passwordError', false);
        $this->smarty->display('Admin/login.tpl');
    }

    /**
     * loginError
     * 
     * This method is used to show the login page with an error message
     */
    public function loginError(){
        $this->smarty->assign('passwordError', true);
        $this->smarty->display('Admin/login.tpl');
    }

    /**
     * profile
     * 
     * This method is used to show the profile of a user
     * @param array $users
     * @param int $count
     * @param ?string $modalMessage
     */
    public function profile(EStudent | EOwner $user, string $userType, array $reviewsData, ?int $reportId, ?string $modalMessage){
        $this->smarty->assign('user', $user);
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->assign('userType', $userType);
        $this->smarty->assign('reportId', $reportId);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/profile.tpl');
    }
    /**
     * readMoreSuppoeRequest
     * 
     * This method is used to show more support requests
     * @param array $reservations
     * @param int $count
     * @param ?string $modalMessage
     */
    public function readMoreSupportRequest(array $requests, int $count, ?string $modalMessage){ 
        $this->smarty->assign('requests', json_encode($requests));
        $this->smarty->assign('count', $count);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/supportRequests.tpl');
    }
    /**
     * readMoreReports
     * 
     * This method is used to show more reports
     * @param array $reservations
     * @param int $count
     * @param ?string $modalMessage
     */
    public function readMoreReports(array $reports, int $count, ?string $modalMessage){
        $this->smarty->assign('reports', json_encode($reports));
        $this->smarty->assign('count', $count);
        $this->smarty->assign('modalMessage', $modalMessage);
        $this->smarty->display('Admin/reports.tpl');
    }
}