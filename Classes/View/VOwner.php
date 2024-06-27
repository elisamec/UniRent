<?php

namespace Classes\View;
require __DIR__.'/../../vendor/autoload.php';

use Classes\Entity\EOwner;
use StartSmarty;

class VOwner {
    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    //Mostra la home del proprietario
    public function home() {
        $this->smarty->display('Owner/home.tpl');
    }

    //Mostra la seconda parte della registrazione proprietario
    public function showOwnerRegistration(){
        $this->smarty->display('Owner/register.tpl');
    }

    //Mostra il profilo del proprietarion
    public function profile(EOwner $owner){
        $this->smarty->assign('owner', $owner);
        $this->smarty->display('Owner/personalProfile.tpl');
    }
    public function editProfile(EOwner $owner){
        $this->smarty->assign('owner', $owner);
        $this->smarty->display('Owner/editPersonalProfile.tpl');
    }
    public function contact(){
        $this->smarty->display('Owner/contact.tpl');
    }
    public function about(){
        $this->smarty->display('Owner/about.tpl');
    }
    public function reviews(array $reviewsData){
        $this->smarty->assign('reviewsData', $reviewsData);
        $this->smarty->display('Owner/reviews.tpl');
    }
    
}
